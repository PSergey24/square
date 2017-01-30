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

    <?= $form->field($modelReport, 'court_id')->textInput(['readonly' => true, 'value' => $id])->hiddenInput()->label(false) ?>

    <?= $form->field($modelReport, 'title')->textInput(['maxlength' => true,'placeholder' => 'Введите тему жалобы'])->label(false) ?>

    <?= $form->field($modelReport, 'description')->textArea(['rows' => 6,'placeholder' => 'Напишите суть жалобы'])->label(false) ?>

    <?= $form->field($modelReport, 'user_id')->textInput(['readonly' => true, 'value' => Yii::$app->user->identity->getId()])->hiddenInput()->label(false) ?>

    <!-- <div class="form-group"> -->
        <?= Html::submitButton('Пожаловаться',['class' => 'mid-green-btn']) ?>
    <!-- </div> -->

    <?php ActiveForm::end(); ?>

</div>
