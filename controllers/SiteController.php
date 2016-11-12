<?php

namespace app\controllers;

use app\models\LoginForm;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use app\models\Court;
use DateTime;
use app\models\User;

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
            return $this->redirect('/court',302);
        return $this->render('index.php');
    }

    public function actionProfile() {

        $query = new Query();
        $query_games = new Query();
        $user_id = Yii::$app->user->getId();
        $query->select('court_id')
            ->from('court_bookmark')
            ->where(['user_id' => $user_id]);
        $bookmarks = $query->all();
        $courts = [];
        $games = [];
        $address = array();
        foreach ($bookmarks as $bookmark) {
            $query->select('id, address')
                ->from('court')
                ->where(['id' => $bookmark['court_id']]);
            $court_addr = $query->one();
            $query_games->select('time, need_ball')
                ->from(['game'])
                ->where(['court_id' => $court_addr['id']]);
            $game_rows = $query_games->all();

            foreach ($game_rows as $row) {
                $tm = strtotime($row['time']);
                $current_datetime = new DateTime();
                $current_datetime = date_format($current_datetime, 'Y-m-d');
                $tm_current = strtotime($current_datetime);
                if (date("d", $tm) == date("d", $tm_current))
                    $row['time'] = 'Сегодня ' . date("H:i", $tm);
                else
                    $row['time'] = 'Завтра ' . date("H:i", $tm);
                $row['address'] = $court_addr['address'];
                array_push($games, $row);
            }

//            $all_games = array_merge($game_rows, $games);
            array_push($courts, $court_addr);
        }

        return $this->render('profile.php', [
            'courts' => $courts,
            'games' => $games,
            'username' => User::find('username')->where(['id' => Yii::$app->user->getId()])->one()->username
        ]);
    }
    
    public function actionAbout()
    {
        return 'About ';
    }
}