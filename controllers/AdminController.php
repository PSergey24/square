<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use app\models\Court;
use app\models\SportType;
use app\models\GameUser;
use yii\web\Response;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\forms\GameCreateForm;
use dektrium\user\controllers\AdminController;
use DateTime;
use DateInterval;

/**
 * GameController implements the CRUD actions for Game model.
 */
class AdminController extends AdminController
{
    public function actionStat()
    {
    	$a = 778;
        return $this->render('stat',[
            'a' => $a,
        ]);
    }
    
}
