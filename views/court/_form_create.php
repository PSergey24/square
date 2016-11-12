<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

cakebake\bootstrap\select\BootstrapSelectAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Court */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="court-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal'
    ]);
    ?>

    <?= $form->field($model, 'address')->textInput([
            'maxlength' => true,
            'readonly' => true,
            'placeholder' => 'Установите метку на карте'
        ])
    ?>

    <?= $form->field($model, 'name')->textInput([
        'maxlength' => true,
        'placeholder' => 'Введите описание площадки'
    ]) ?>

    <?= $form->field($model, 'built_up_area')->textInput() ?>

    <div class="form-group field-court-district_city_id required">
        <label class="control-label col-sm-3" for="court-district_city_id">
            <?= $model->getAttributeLabel('district_city_id') ?>
        </label>
        <div class="col-sm-6">
            <?= Html::activeDropDownList($model, 'district_city_id', $district_cities, [
                'class' => 'selectpicker',
                'title' => 'Выберите район'
            ]);
            ?>
            <div class="help-block help-block-error "></div>
        </div>
    </div>
    <div class="form-group field-court-type_id required">
        <label class="control-label col-sm-3" for="court-type_id">
            <?= $model->getAttributeLabel('type_id') ?>
        </label>
        <div class="col-sm-6">
            <?= Html::activeDropDownList($model, 'type_id', $types, [
                'class' => 'selectpicker',
                'title' => 'Выберите тип'
            ]);
            ?>
            <div class="help-block help-block-error "></div>
        </div>
    </div>

    <?= Html::activeHiddenInput($model, 'lat') ?>

    <?= Html::activeHiddenInput($model, 'lon') ?>

    <div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
