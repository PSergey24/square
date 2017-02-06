<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Report;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Court */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-form">

    <?php  Pjax::begin([
                    'id' => 'report-create',
                    'enablePushState' => false
                ]);
    ?>
    <?php 
    $string = '/court/'.$id;
    $form = ActiveForm::begin([
                    'options' => ['class' => 'form-horizontal', 'data-pjax' => ''
                    ],
                    'id' => 'report-create-form',
                    'action' => $string
                ]); ?>

    <?php echo $form->field($modelReport, 'court_id')->hiddenInput(['value' => $id])->label(false); ?>

    <?= $form->field($modelReport, 'title')->textInput(['maxlength' => true,'placeholder' => 'Введите тему жалобы'])->label(false) ?>

    <?= $form->field($modelReport, 'description')->textArea(['rows' => 6,'placeholder' => 'Напишите суть жалобы'])->label(false) ?>

    <?php 
    if(Yii::$app->user->identity)
        echo $form->field($modelReport, 'user_id')->hiddenInput(['value' => Yii::$app->user->identity->getId()])->label(false);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Пожаловаться',['class' => 'mid-green-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php  Pjax::end();  ?>

</div>
