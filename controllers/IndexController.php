<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

class IndexController extends Controller
{
    public function actionIndex() {
        return $this->render('index.php');
    }

    public function actionAbout()
    {
        return 'About action    ';
    }
    
}