<?php

use yii\helpers\Html;
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
    <?= $form->field($model, 'district_city_id')
                ->dropDownList($district_cities, [
                    'class' => 'selectpicker',
                    'title' => 'Выберите район'
                ]);
    ?>

    <?= $form->field($model, 'type_id')
            ->dropDownList($types, [
                'class' => 'selectpicker',
                'title' => 'Выберите тип'
            ]);
    ?>
    <?= Html::activeHiddenInput($model, 'lat') ?>

    <?= Html::activeHiddenInput($model, 'lon') ?>

    <?php echo $form->field($model, 'approved')->hiddenInput(['value' => '1'])->label(false); ?>

    <div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
