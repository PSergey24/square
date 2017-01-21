<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\SettingsForm $model_account
 */

$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/css/settings.css');
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<div class="container">
    <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12">
        <div class="row">
            <div class="col-md-3">
                <?= $this->render('_menu') ?>
            </div>
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= Html::encode($this->title) ?>
                    </div>
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'profile-form',
                            'options' => ['class' => 'form-horizontal'],
                            'fieldConfig' => [
                                'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                                'labelOptions' => ['class' => 'col-lg-3 control-label'],
                            ],
                            'enableAjaxValidation'   => false,
                            'enableClientValidation' => false,
                            'validateOnBlur'         => false,
                        ]); ?>

                        <?= $form->field($model, 'picture')->fileInput()->label('Фото профиля') ?>
                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-9">
                                <?= \yii\helpers\Html::submitButton(
                                    Yii::t('user', 'Save'),
                                    ['class' => 'btn btn-block btn-success']
                                ) ?><br>
                            </div>
                        </div>
                        <?php \yii\widgets\ActiveForm::end(); ?>

                        <?php $form_account = ActiveForm::begin([
                            'id'          => 'account-form',
                            'options'     => ['class' => 'form-horizontal'],
                            'fieldConfig' => [
                                'template'     => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                                'labelOptions' => ['class' => 'col-lg-3 control-label'],
                            ],
                            'enableAjaxValidation'   => true,
                            'enableClientValidation' => false,
                        ]); ?>

                        <?= $form_account->field($model_account, 'username') ?>

                        <?= $form_account->field($model_account, 'new_password')->passwordInput() ?>

                        <?= $form_account->field($model_account, 'current_password')->passwordInput() ?>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-9">
                                <?= \yii\helpers\Html::submitButton(
                                    Yii::t('user', 'Save'),
                                    ['class' => 'btn btn-block btn-success']
                                ) ?><br>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

