<?php

use app\assets\AppAsset;

/* @var $this yii\web\View */

$this->registerCssFile('/css/start.css',[
    'depends' => [AppAsset::className()]
]);

$this->title = 'Квадрат';
$this->params['model'] = $model;
$this->params['districts'] = $districts;
$this->params['sport_types'] = $sport_types;

?>

<div class="container mid" id="mid">
    <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12"><h1 class="h1-black">Квадрат поможет</h1></div>
    <div class="kv-is col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <img src="img/4r.png">
        <p>Создать игру<br>на любой уличной площадке</p>
    </div>
    <div class="kv-is col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <img src="img/3r.png">
        <p>Встретить<br>новых друзей</p>
    </div>
    <div class="kv-is col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <img src="img/1r.png">
        <p>Найти все площадки<br> твоего города</p>
    </div>
    <div class="kv-is col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <img src="img/2r.png">
        <p>Заниматься<br>спортом</p>
    </div>

</div>

<div class="container-fluid bg">
    <div class="container">
        <h2 class="h2-white">Присоединяйся к Квадрату прямо сейчас :)</h2>
        <a href="/login" class="big-green-btn shadow">Регистрация</a>
    </div>
</div>
