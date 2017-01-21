<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $user
 */

$this->title = Yii::t('user', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
    <?php $form = ActiveForm::begin([
        'id' => 'registration-form',
    ]); ?>

    <?= $form->field($model, 'username', ['inputOptions' =>
        ['placeholder' => 'Логин']])->label(false) ?>

    <?= $form->field($model, 'password', ['inputOptions' =>
        ['placeholder' => 'Пароль']])->passwordInput()->label(false) ?>

    <?= $form->field($model, 'password_repeat', ['inputOptions' =>
        ['placeholder' => 'Повторите пароль']])->passwordInput()->label(false) ?>

    <?= Html::submitButton(Yii::t('user', 'Присоединиться'), ['class' => 'btn btn-success btn-block']) ?>

    <?php ActiveForm::end(); ?>

    <fieldset>
        <legend>Через социальные сети</legend>
        <a href="/user/security/auth?authclient=vkontakte"><i class="fa fa-vk fa-lg" aria-hidden="true"></i></a>
        <a href="/user/security/auth?authclient=facebook"><i class="fa fa-facebook fa-lg" aria-hidden="true"></i></a>
    </fieldset>
