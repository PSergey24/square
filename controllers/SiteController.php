<?php

namespace app\controllers;

use app\custom\HTMLSelectData;
use app\models\DistrictCity;
use app\models\LoginForm;
use app\models\SportType;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use DateTime;
use app\models\User;
use app\models\Profile;
use app\models\MapFilters;

class SiteController extends Controller
{

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
                ->andWhere('flag_moderation = 0');

            if($query->one())
            {
                $photo[$i] = $query->one();
            }
            else
                $photo[$i]['photo'] = 'defaultCourt.png';
            $i++;
        }
        $games = [];

            $query_games->select('court.id as court_id, address, time, need_ball')
                ->from('court,game,game_user')
                ->where(['user_id' => $user_id])
                ->andWhere('game_id = game.id')
                ->andWhere('court_id = court.id')
                ->andWhere(['>=', 'time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))])
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
             
        return $this->render('profile.php', [
            'courts' => $courts,
            'games' => $games,
            'username' => User::find('username')->where(['id' => Yii::$app->user->getId()])->one()->username,
            'photo' => $photo,
        ]);
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
                ->andWhere('flag_moderation = 0');

            if($query->one())
            {
                $photo[$i] = $query->one();
            }
            else
                $photo[$i]['photo'] = 'defaultCourt.png';
            $i++;
        }

        $games = [];

            $query_games->select('court.id as court_id, address, time, need_ball')
                ->from('court,game,game_user')
                ->where(['user_id' => $id])
                ->andWhere('game_id = game.id')
                ->andWhere('court_id = court.id')
                ->andWhere(['>=', 'time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))])
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
    
    public function actionAbout()
    {
        return 'About ';
    }
}