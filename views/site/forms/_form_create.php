<?php

 /* @var $this yii\base\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\db\Query;
use yii\helpers\ArrayHelper;

cakebake\bootstrap\select\BootstrapSelectAsset::register($this);

?>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content game-create create-game">
            <i class="fa fa-times close fa-lg " data-dismiss="modal" aria-hidden="true" id="close"></i>
            <p class="h2-black">Создание игры</p>

            <?php 
                Pjax::begin([
                    'id' => 'game-create',
                    'enablePushState' => false
                ]);
                $form = ActiveForm::begin([
                    'options' => ['class' => 'form-horizontal', 'data-pjax' => ''
                    ],
                    'id' => 'game-create-form',
                    'action' => '/site/create'
                ]);
            
            ?>
            <p>
            <?php
                $items = ArrayHelper::map($courts,'id','address');
            ?>
            <p class="little">Выберите площадку</p>
            <?php
            echo $form->field($model,'court_id')->dropDownList($items,[
                    'class' => 'selectpicker .form-control date',
                    'id' => '1'
                ])->label(false);
            ?>
            </p>
            <p class="little">Выберите время игры</p>
            <p class="align-right" id="align-right">
                <?= Html::activeDropDownList($model, 'day',
                    [
                        '0' => 'Сегодня',
                        '1' => 'Завтра'
                    ],
                    [
                        'class' => 'selectpicker .form-control date-2',
                        'required' => true
                    ]
                );
                ?>
                <?= $form->field($model, 'time_digit')->input('time', [
                        'class' => 'input date-2',
                        'id' => 'time'
                    ])
                    ->label(false)
                    ->error(false)
                ?>

               
            </p>
            <p class="ball">
                <?= Html::activeCheckbox($model, 'need_ball', ['label'   => 'Мяч есть']); ?>


            </p>
            <?= Html::submitButton('Готово', ['class' => 'mid-green-btn']) ?>
    

            <?php ActiveForm::end();
                Pjax::end();
            ?>

        </div>
    </div>
</div>