<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
]) ?>

<?= $form->field($model, 'login', [
    'inputOptions' => [
        'tabindex' => '1',
        'placeholder' => 'Логин'
    ]])->label(false) ?>

<?= $form->field($model, 'password', [
    'inputOptions' => [
        'tabindex' => '2',
        'placeholder' => 'Пароль'
    ]])->passwordInput()->label(false)?>

<?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

<?= Html::submitButton(
    Yii::t('user', 'Sign in'),
    ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']
) ?>

<?php ActiveForm::end(); ?>
<fieldset>
    <legend>Через социальные сети</legend>
    <a href="/user/security/auth?authclient=vkontakte"><img src="../img/vk.png"></a>
    <a href="/user/security/auth?authclient=facebook"><img src="../img/facebook.png"></a>
</fieldset>