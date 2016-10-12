<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

    $link = Yii::$app->user->isGuest ?
        ['label' => 'Войти', 'url' => ['/login']] :
        ['label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
            'url' => ['/logout'],
            'linkOptions' => ['data-method' => 'post']];
    ['label' => 'Зарегистрироваться', 'url' => ['/register'], 'visible' => Yii::$app->user->isGuest];

    $url = Html::a($link['label'], $link['url'][0],['class' => 'btn btn-success',
        'data-method' => isset($link['linkOptions']['data-method'])]);

?>
<?= $url ?>
