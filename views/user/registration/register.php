<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View              $this
 * @var yii\widgets\ActiveForm    $form
 * @var dektrium\user\models\User $user
 */

$this->title = Yii::t('user', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'registration-form',
                ]); ?>

                <?= $form->field($model, 'username', ['inputOptions' =>
                    ['autofocus' => 'autofocus', 'class' => 'form-control',
                        'placeholder' => 'Логин']])->label(false) ?>

                <?= $form->field($model, 'password', ['inputOptions' =>
                    ['class' => 'form-control',
                        'placeholder' => 'Пароль']])->passwordInput()->label(false) ?>

                <?= $form->field($model, 'password_repeat', ['inputOptions' =>
                    ['autofocus' => 'autofocus','class' => 'form-control',
                        'placeholder' => 'Повторите пароль']])->passwordInput()->label(false) ?>

                <?= Html::submitButton(Yii::t('user', 'Присоединиться'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>