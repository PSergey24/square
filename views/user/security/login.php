<?php

use dektrium\user\widgets\Connect;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\LoginForm $model_login
 * @var app\models\RegistrationForm $model_register
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Регистрация/Авторизация');
?>

<div id="container-form" class="col-lg-offset-4 col-lg-4 col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-12">
    <div class="tabs">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-1" data-toggle="tab">Регистрация</a></li>
            <li class=""><a href="#tab-2" data-toggle="tab">Вход</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab-1">
                <?= $this->render('@app/views/user/registration/register.php', ['model' => $model_register]); ?>
            </div>
            <div class="tab-pane" id="tab-2">
                <?= $this->render('@app/views/user/security/login_auth_form.php', [
                    'model' => $model_login, 'module' => $module
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
