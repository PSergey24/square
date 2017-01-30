<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Report;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Court */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($modelReport, 'court_id')->hiddenInput(['value' => $id])->label(false); ?>

    <?= $form->field($modelReport, 'title')->textInput(['maxlength' => true,'placeholder' => 'Введите тему жалобы'])->label(false) ?>

    <?= $form->field($modelReport, 'description')->textArea(['rows' => 6,'placeholder' => 'Напишите суть жалобы'])->label(false) ?>

    <?php echo $form->field($modelReport, 'user_id')->hiddenInput(['value' => Yii::$app->user->identity->getId()])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('Пожаловаться',['class' => 'mid-green-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
