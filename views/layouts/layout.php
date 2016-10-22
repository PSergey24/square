<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;
AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
<!--    <header>-->
        <div id="menu" class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="container">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a href="/" id="logo" class="hidden-xs"><?= Html::img(Yii::getAlias('@web') . '/img/logo.png') ?></a>
                        <a href="#" id="logo" class="visible-xs"><?= Html::img(Yii::getAlias('@web') . '/img/logo1.png') ?></a>
                        <ul>
                            <a href="#"><li><img src="#" class="visible-xs"><span>Площадки</span></li></a>
                            <a href="#"><li><img src="#" class="visible-xs"><span>Игры</span></li></a>
                        </ul>
                        <div class="collapse " id="responsive-menu">
                            <a href="/login" id="registration" class="blue-big-button">Регистрация</a>
                            <a href="#" id="enter">Вход</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--    </header>-->
    <div class="container">
        <?php $link_attr = Yii::$app->user->isGuest ?
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
            $main_page_link = Html::a('Главная', '/',['class' => 'btn btn-info pull-right']);
        ?>

        <?php
        if (strpos(Url::current(), 'login') == false)
            echo $reg_login_link;
        
        $disable_profile_link = ['settings', 'login'];
        foreach ($disable_profile_link as $path) {
            if (strpos(Url::current(), $path) == true)
                $found = 1;
        }
        if (isset($found))
            echo $main_page_link;
        else
            echo $profile_link;


        ?>
    </div>
    <div class="container">
        <?= $content ?>
    </div>
    
    <div id="footer" class="container-fluid footer">
        <a href="#">Обратная связь</a>
        <a href="#">О компании</a>
        <a href="#">Пользовательское соглашение</a>
        <p>Квадрат 2016</p>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>