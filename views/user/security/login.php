<?php

use dektrium\user\widgets\Connect;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View                     $this
* @var yii\widgets\ActiveForm           $form
* @var dektrium\user\models\LoginForm   $model_login
* @var app\models\RegistrationForm      $model_register
* @var dektrium\user\Module             $module
*/

$this->title = Yii::t('user', 'Регистрация/Авторизация');
?>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4" >
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><?= Html::encode('Регистрация') ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'registration-form',
                ]); ?>

                <?= $form->field($model_register, 'username', ['inputOptions' =>
                    ['autofocus' => 'autofocus', 'class' => 'form-control',
                        'placeholder' => 'Логин']])->label(false) ?>

                <?= $form->field($model_register, 'password', ['inputOptions' =>
                    ['autofocus' => 'autofocus','class' => 'form-control',
                        'placeholder' => 'Пароль']])->passwordInput()->label(false) ?>

                <?= $form->field($model_register, 'password_repeat', ['inputOptions' =>
                    ['autofocus' => 'autofocus','class' => 'form-control',
                        'placeholder' => 'Повторите пароль']])->passwordInput()->label(false) ?>

                <?= Html::submitButton(Yii::t('user', 'Присоединиться'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <p class="text-center">
            <?= Html::a(Yii::t('user', 'Уже зарегистрированы? Войдите в свой аккаунт'), ['/user/security/login']) ?>
        </p>
    </div>
<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><?= Html::encode('Авторизация') ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id'                     => 'login-form',
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                    'validateOnBlur'         => false,
                    'validateOnType'         => false,
                    'validateOnChange'       => false,
                ]) ?>

                <?= $form->field(
                    $model_login,
                    'login',
                    ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
                ) ?>

                <?= $form
                    ->field(
                        $model_login,
                        'password',
                        ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']]
                    )
                    ->passwordInput()
                    ->label(
                        Yii::t('user', 'Password')
                        .($module->enablePasswordRecovery ?
                            ' (' . Html::a(
                                Yii::t('user', 'Forgot password?'),
                                ['/user/recovery/request'],
                                ['tabindex' => '5']
                            )
                            . ')' : '')
                    ) ?>

                <?= $form->field($model_login, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

                <?= Html::submitButton(
                    Yii::t('user', 'Sign in'),
                    ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']
                ) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?php if ($module->enableConfirmation): ?>
            <p class="text-center">
                <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
            </p>
        <?php endif ?>
        <?php if ($module->enableRegistration): ?>
            <p class="text-center">
                <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
            </p>
        <?php endif ?>
        <?= Connect::widget([
            'baseAuthUrl' => ['/user/security/auth'],
        ]) ?>
    </div>
</div>