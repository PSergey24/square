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
    }

    public function actionAuth() {
        
    }

    public function actionRegister() {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $user = new User();
            $user->login = $request->post('login');
            $user->password = $request->post('password');
//            try {
                $user->save();
//            } catch (Exception $e) {
//                return $e;
//            }
        }
        return $this->render('register.twig', ['model' => new LoginForm()]);
    }

    public function actionAbout()
    {
        return 'About action    ';
    }

    public function actionContact()
    {

    }
}