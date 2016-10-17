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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/site.css">
</head>
<body>
    <?php $this->beginBody() ?>
    <header
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
    </header>
    <div class="container">
        <?= $content ?>
    </div>
    <footer></footer>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>