<?php

namespace app\controllers;

use Yii;
use app\custom\HTMLSelectData;
use app\models\DistrictCity;
use app\models\LoginForm;
use app\models\SportType;
use app\models\CourtBookmark;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use DateTime;
use DateInterval;
use app\models\User;
use app\models\Game;
use app\models\GameUser;
use app\models\Profile;
use app\models\MapFilters;
use app\models\forms\GameCreateForm;

class SiteController extends Controller
{
    const SUCCESS_CREATE_BOX =
        '<i class="fa fa-times close fa-lg" aria-hidden="true" data-dismiss="modal" ></i>
            <i class="fa fa-check fa-4x ok" aria-hidden="true"></i>';
    const ERROR_CREATE_BOX =
        '<i class="fa fa-times close fa-lg" aria-hidden="true" data-dismiss="modal" ></i>
            <i class="fa fa-times fa-4x" aria-hidden="true"></i>';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
    
    public function actionIndex() {

        if (!Yii::$app->user->isGuest)
            return $this->redirect('/profile',302);

        $filters = Yii::createObject(MapFilters::className());

        $districts = HTMLSelectData::get_list_for_select(new DistrictCity());
        $sport_types = HTMLSelectData::get_list_for_select(new SportType());

        return $this->render('index.php', [
            'model'  => $filters,
            'districts' => $districts,
            'sport_types' => $sport_types
        ]);
    }

    public function actionProfile() {
        if(!Yii::$app->user->identity)
            die('Вы не авторизованы');

        $model_form_game_create = Yii::createObject(GameCreateForm::className());
        $users = array();
        // получаем id авторизованного пользователя
            if(Yii::$app->user->identity)
                $userAuth = Yii::$app->user->identity->getId();
            else
                $userAuth = 0;
        $query = new Query();
        $query_games = new Query();
        $user_id = Yii::$app->user->getId();
        $query->select('court.id as id, address')
            ->from('court_bookmark, court')
            ->where(['user_id' => $user_id])
            ->andWhere('court_id = court.id');
        $courts = $query->all();
        $photo = [];
        $i=0;
        foreach ($courts as $court) {
            $query->select('photo')
                ->from('court_photo')
                ->where(['court_id' => $court['id']])
                ->andWhere('avatar = 1')
                ->andWhere('approved = 0');

            if($query->one())
            {
                $photo[$i] = $query->one();
            }
            else
                $photo[$i]['photo'] = 'defaultCourt.jpg';
            $i++;
        }

        // выборка игр, в которых участвует пользователь
        $query = new Query;
        $query->select('game.id as id,game_id,time, sport_type.name as sport,sport_type_id,need_ball,COUNT(game_id) as count,address,court_id')
              ->from('game_user,game,sport_type,court')
              ->where(['user_id' => $userAuth])
              ->andWhere('game_id = game.id')
              ->andWhere(['>=','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')))])
              ->andWhere('court_id = court.id')
              ->andWhere('sport_type_id = sport_type.id');
        $games = $query->groupBy('game.id')->orderBy('time')->all();

            $i = 0;
            foreach ($games as $gameItem) {
                    $queryPlayer = new Query;
                    $queryPlayer->select('game_user.user_id as user_id, picture')
                        ->from('game_user, profile')
                        ->where(['game_id' => $gameItem['id']])
                        ->andWhere('game_user.user_id = profile.user_id');
                    $players = $queryPlayer->orderBy('game_user.id desc')->all();
                    array_push($users,$players);
                    $plus = '+';
                    foreach ($players as $player) {
                        if($player['user_id'] == $userAuth)
                            $plus = '-';
                    }
                    $games[$i]['plus'] = $plus;
                $i++;
            }
        // конец выборки игр, в которых участвует пользователь

        // выборка игр с площадок пользователя. Но в них он еще не участвует
        $query2 = new Query;
        $query2->select('game.id as id,game_id,time, sport_type.name as sport,sport_type_id,need_ball,COUNT(game_id) as count,address,game.court_id as court_id')
              ->from('game_user,game,sport_type,court,court_bookmark')
              ->where(['court_bookmark.user_id' => $userAuth])
              ->andWhere('court_bookmark.court_id = game.court_id')
              ->andWhere('game_id = game.id')
              ->andWhere(['>=','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')))])
              ->andWhere('game.court_id = court.id')
              ->andWhere('sport_type_id = sport_type.id');
        $games2 = $query2->groupBy('game.id')->orderBy('time')->all();

            foreach ($games2 as $gameItem) {
                    $queryPlayer = new Query;
                    $queryPlayer->select('game_user.user_id as user_id, picture')
                        ->from('game_user, profile')
                        ->where(['game_id' => $gameItem['id']])
                        ->andWhere('game_user.user_id = profile.user_id');
                    $players = $queryPlayer->orderBy('game_user.id desc')->all();

                    $flag = 0;
                    foreach ($players as $player) {
                        if($player['user_id'] == $userAuth)
                            $flag = 1;
                    }
                    if($flag == 0)
                    {
                        array_push($users,$players);
                        $games[$i] = $gameItem;
                        $games[$i]['plus'] = '+';
                    }
                $i++;
            }
        // конец выборки игр с площадок пользователя. Но в них он еще не участвует

             
        return $this->render('profile.php', [
            'courts' => $courts,
            'games' => $games,
            'users' => $users,
            'username' => User::find('username')->where(['id' => Yii::$app->user->getId()])->one()->username,
            'photo' => $photo,
            'model_form_game_create' => $model_form_game_create
        ]);
    }

    public function actionDelete_court()
    {
        $id = Yii::$app->getRequest()->getBodyParam("id");
        $court = Yii::createObject(CourtBookmark::className());
        $courtDelete = $court->findOne(['court_id' => $id, 'user_id' => Yii::$app->user->getId()]);
        
        if($courtDelete->delete())
            return $id;
        else
            return 'ошибка';
    }

    public function actionUsers($id) {

        $query = new Query();
        $query_games = new Query();

        $query->select('court.id as id, address')
            ->from('court_bookmark, court')
            ->where(['user_id' => $id])
            ->andWhere('court_id = court.id');
        $courts = $query->all();
        $photo = [];
        $i=0;
        foreach ($courts as $court) {
            $query->select('photo')
                ->from('court_photo')
                ->where(['court_id' => $court['id']])
                ->andWhere('avatar = 1')
                ->andWhere('approved = 0');

            if($query->one())
            {
                $photo[$i] = $query->one();
            }
            else
                $photo[$i]['photo'] = 'defaultCourt.jpg';
            $i++;
        }

        $games = [];

            $query_games->select('court.id as court_id, address, time, need_ball')
                ->from('court,game,game_user')
                ->where(['user_id' => $id])
                ->andWhere('game_id = game.id')
                ->andWhere('court_id = court.id')
                ->andWhere(['>=', 'time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')))])
                ->orderBy('time');
            $game_rows = $query_games->all();

            foreach ($game_rows as $row) {
                $tm = strtotime($row['time']);
                $current_datetime = new DateTime();
                $current_datetime = date_format($current_datetime, 'Y-m-d');
                $tm_current = strtotime($current_datetime);
                if (date("d", $tm) == date("d", $tm_current))
                    $row['time'] = 'Сегодня ' . date("H:i", $tm);
                elseif(date("d", $tm) == date(date("d")+1, $tm_current))
                    $row['time'] = 'Завтра ' . date("H:i", $tm);
                else
                    $row['time'] = date("d.m.Y", $tm) ." ".date("H:i", $tm);
                array_push($games, $row);
            }

        $query->select('picture')
            ->from('profile')
            ->where(['user_id' => $id]);
        $picture = $query->one();

        return $this->render('users.php', [
            'courts' => $courts,
            'games' => $games,
            'username' => User::find('username')->where(['id' => $id])->one()->username,
            'picture' => $picture,
            'photo' => $photo,
        ]);
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isPjax) {

            $model = Yii::createObject(GameCreateForm::className());

            $model->load(Yii::$app->request->post());

            if (Yii::$app->user->isGuest)
                throw new UserException('Для того чтобы создать игру необходимо авторизоваться');

            $model->creator_id = Yii::$app->user->getId();

            $date = new DateTime();
            if ($model->day) {
                $date->add(new DateInterval('P1D'));
            }

            $time = $model->time_digit;
            $datetime = date_format($date, 'Y-m-d') . ' ' . $time;
            $now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
            
            if($datetime < $now)
                return self::ERROR_CREATE_BOX . '<p id="warning">Данное время уже прошло. Исправьте время.</p>';

            $model->time = $datetime;

            $query = new Query();
            $query->select('type_id')
                ->from('court')
                ->where(['id' => $model->court_id]);
            $type = $query->one();

            $model->sport_type_id = $type['type_id'];

            //check if game already exist
            if (Game::findOne(['court_id' => $model->court_id,'time' => $model->time]))
                return self::ERROR_CREATE_BOX . '<p id="warning">Игра уже существует</p>';

            $model->save();

            // Создается запись в таблице game_user
            $model2 = Yii::createObject(GameUser::className());
            $model2->user_id = $model->creator_id;
            $model2->game_id = $model->id;
            $model2->save();

            return self::SUCCESS_CREATE_BOX  . '<p id="warning">Игра успешно создана</p>';

        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionAbout()
    {
        return 'About ';
    }
}