<?php

use yii\helpers\Html;
use app\assets\AppAsset;

$this->registerCssFile('/css/userProfile.css',[
    'depends' => [AppAsset::className()]
]);
?>

<div class="container-fluid top">
    <div class="container s">

        <div class="top-info">
            <div class="userPhoto"></div>
            <h1 class="h1-white">SuperBro</h1>
            <p><a href="#" class="tag">Футбол</a><a href="#" class="tag">Баскетбол</a></p>
            <p>Живет: <a class="live" href="#">Курляндская ул. 45</a></p>
        </div>
    </div>
</div>

<div class="container-fluid contentUser">
    <div class="container">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 forSmall">
            <div class="box contentUserBox col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="h1-black">Площадки SuperBro <span><?= count($courts) ?></span></h2>
                <?php
                    foreach ($courts as $court) {
                        echo '<a href=/court/view/' . $court["id"] . '><div class="contentUserImg"><p>'. $court["address"] . '</p></div></a>';
                    }
                ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 forSmall">
            <div class="box contentUserBox col-lg-12 col-md-12 col-sm-12 col-xs-12 soonGame" id="games">
                <h2 class="h1-black">Ближайшие игры/тренировки</h2>
                <?php
                    foreach ($games as $game){
                        if($game['need_ball'])
                            $ballImg = '/img/ball-ok.png';
                        else
                            $ballImg = '/img/ball-not.png';
                        echo '<div class="timeBox">
                                <p class="timeGame">'.$game['time'].'</p><img class="timeBall" src="'.$ballImg.'"><a class="timeGameAddress" href="#">'.$game['address'].'</a>
                            </div>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>
