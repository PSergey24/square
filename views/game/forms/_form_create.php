<?php

 /* @var $this yii\base\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

cakebake\bootstrap\select\BootstrapSelectAsset::register($this);

?>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
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
                    'action' => '/game/create'
                ]);
            ?>
            <?= Html::activeHiddenInput($model, 'court_id', [
                'value' => Yii::$app->getRequest()->getQueryParam('id')
            ]);
            ?>
            <p>
            <p class="little">Выберите вид спорта</p>
            <?= Html::activeDropDownList($model, 'sport_type_id',
                [
                    '1' => 'Баскетбол',
                    '2' => 'Футбол'
                ],
                [
                    'class' => 'selectpicker input date',
                ]
            );
            ?>
            </p>
            <p class="little">Выберите время игры</p>
            <p class="align-right">
                <?= Html::activeDropDownList($model, 'day',
                    [
                        '0' => 'Сегодня',
                        '1' => 'Завтра'
                    ],
                    [
                        'class' => 'selectpicker input date',
                        'required' => true
                    ]
                );
                ?>
                <?= $form->field($model, 'time_digit')->input('time', [
                        'class' => 'input date',
                        'id' => 'time'
                    ])
                    ->label(false)
                    ->error(false)
                ?>

               
            </p>
            <p class="ball">
                <?= Html::activeCheckbox($model, 'need_ball', ['label'   => 'Нужен мяч']); ?>


            </p>
            <?= Html::submitButton('Готово', ['class' => 'mid-green-btn']) ?>
    

            <?php ActiveForm::end();
                Pjax::end();
            ?>

        </div>
    </div>
</div>