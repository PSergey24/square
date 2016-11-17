<?php

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\models\Profile;

$this->registerCssFile('/css/userProfile.css',[
    'depends' => [AppAsset::className()]
]);

$this->params['picture_href'] = Profile::getAvatar();

?>

<div class="container-fluid top">
    <div class="container s">

        <div class="top-info">
            <img class="userPhoto" src="<?= $this->params['picture_href']  ?>">

            <h1 class="h1-white"><?= $username ?></h1>
            <p>
                <?= Html::a('Футбол', Url::to(['/court', 'sport_type' => 2], true), ['class' => 'tag']); ?>
                <?= Html::a('Баскетбол', Url::to(['/court', 'sport_type' => 1], true), ['class' => 'tag']); ?>
            </p>
            <p>Живет: <a class="live" href="#">Курляндская ул. 45</a></p>
        </div>
    </div>
</div>

<div class="container-fluid contentUser">
    <div class="container">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 forSmall">
            <div class="box contentUserBox col-lg-12 col-md-12 col-sm-12 col-xs-12 shadow">
                <h2 class="h2-black">Площадки <?= $username ?> <span><?= count($courts) ?></span></h2>
                <?php
                    foreach ($courts as $court) {
                        echo '<a href=/court/' . $court["id"] . '><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 wrap"><div class="contentUserImg"><p>'. $court["address"] . '</p></div></div></a>';
                    }
                ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 forSmall">
            <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12 soonGame shadow" id="games">
                <h2 class="h2-black">Ближайшие игры/тренировки</h2>
                <?php
                    foreach ($games as $game){
                        if($game['need_ball'])
                            $ballImg = '/img/ball-ok.png';
                        else
                            $ballImg = '/img/ball-not.png';
                        echo '<div class="timeBox">
                                <p class="timeGame">'.$game['time'].'</p><img class="timeBall" src="'.$ballImg.'"><a class="timeGameAddress" href="/court/' . $game['court_id'] . '">'.$game['address'].'</a>
                            </div>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>
