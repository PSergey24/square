<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

    $link_attr = Yii::$app->user->isGuest ?
        ['label' => 'Авторизоваться', 'url' => ['/login']] :
        ['label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
            'url' => ['/logout'],
            'linkOptions' => ['data-method' => 'POST']];
    ['label' => 'Зарегистрироваться', 'url' => ['/register'], 'visible' => Yii::$app->user->isGuest];

    //If logout action  we need to set up POST link method
    if (isset($link_attr['linkOptions']['data-method']))
        $data_method = $link_attr['linkOptions']['data-method'];
    else
        $data_method = ''; //default - GET link method

    if ($link_attr['url'][0] == '/logout')
        $link_attr['class'] = 'btn btn-danger pull-right';
    else
        $link_attr['class'] = 'btn btn-success pull-right';

$reg_login_link = Html::a($link_attr['label'], $link_attr['url'][0],['class' =>  $link_attr['class'],
        'data-method' => $data_method]);
    $profile_link = Html::a('Профиль', 'settings/profile',['class' => 'btn btn-info pull-right']);
?>

<?= $reg_login_link ?>
<?= $profile_link ?>
