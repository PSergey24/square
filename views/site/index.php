<?php

use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this yii\web\View */

$this->registerCssFile('/css/start.css',[
    'depends' => [AppAsset::className()]
]);

$this->registerJs("
    $('#submit').click(function(){
        location.href = document.URL + 'court?sport_type=' + $('#sport_type').val();
    });
");
?>

<div class="container mid" id="mid">
    <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12"><h1 class="h1-black">Что такое Квадрат?</h1></div>
    <div class="kv-is col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <img src="img/4th.png">
        <p>Приложение,<br>позволяющее создавать и находить игры</p>
    </div>
    <div class="kv-is col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <img src="img/2nd.png">
        <p>Сайт, на котором<br>можно найти новых друзей</p>
    </div>
    <div class="kv-is col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <img src="img/1st.png">
        <p>Место, где есть<br>все площадки твоего города</p>
    </div>
    <div class="kv-is col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <img src="img/3rd.png">
        <p>Площадка для<br>активной и спортивной молодежи</p>
    </div>

</div>

<div class="container-fluid bg">
    <div class="container">
        <h1 class="h1-white">Присоединяйся к Квадрату прямо сейчас :)</h1>
        <a href="#" class="big-green-btn shadow">Регистрация</a>
    </div>
</div>
