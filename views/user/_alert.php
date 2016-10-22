<?php

use yii\bootstrap\Alert;

/**
 * @var dektrium\user\Module $module
 */
?>
<?php if ($module->enableFlashMessages): ?>
    <div class="row">
        <div class="col-xs-12">
            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
                    <?= Alert::widget([
                        'options' => ['class' => 'alert-dismissible alert-' . $type . ' text-center'],
                        'body' => $message
                    ]) ?>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>