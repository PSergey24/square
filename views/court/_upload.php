<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Court */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="upload-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label(false) ?>

    <button>Загрзуить фото</button>

	<?php ActiveForm::end() ?>

</div>
