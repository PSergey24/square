<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DistrictCity;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Court */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="court-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'built_up_area')->textInput() ?>

    <?= $form->field($model, 'creator_id')->textInput(['readonly' => true, 'value' => Yii::$app->user->identity->getId()]) ?>

    <?= $form->field($model, 'district_city_id')->textInput() ?>

    <?= $form->field($model, 'type_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
