<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Court */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="upload-form">

	<?php  Pjax::begin([
           'id' => 'photo-upload',
           'enablePushState' => false
    ]);
    ?>

    <?php 
    $string = '/court/'.$id;
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'data-pjax' => ''],
    	'id' => 'photo-upload-form',
        'action' => $string
    ]) ?>

    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label(false) ?>

    <button>Загрзуить фото</button>

	<?php ActiveForm::end() ?>

	<?php  Pjax::end();  ?>

</div>
