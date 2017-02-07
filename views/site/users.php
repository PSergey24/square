<?php

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\models\Profile;

$this->registerCssFile('/css/userProfile.css',[
    'depends' => [AppAsset::className()]
]);
$this->registerCssFile('/css/squareProfile.css',[
    'depends' => [AppAsset::className()]
]);
if(Yii::$app->user->identity)
    $userAuth = Yii::$app->user->identity->getId();
else
    $userAuth = 0;
?>

<div class="container-fluid top">
    <div class="container s">

        <div class="top-info">
            <img class="userPhoto" src="../../img/uploads/<?= $picture['picture'] ?>">

            <h1 class="userName"><?= $username ?></h1>
            <p>
                <?= Html::a('Футбол', Url::to(['/court', 'sport_type' => 2], true), ['class' => 'tag']); ?>
                <?= Html::a('Баскетбол', Url::to(['/court', 'sport_type' => 1], true), ['class' => 'tag']); ?>
            </p>

        </div>
        <div class="options">
            <div class="col-xs-12 visible-xs call">Позвать <?= $username ?> на: </div>
            <div class="mid-green-btn"><i class="fa fa-user-plus" aria-hidden="true"></i>
                <span class=""><span class="hidden-xs">Пригласить на</span> игру</span>
            </div>
            <div class="mid-blue-btn"><i class="fa fa-map-marker" aria-hidden="true"></i>
                <span class=""><span class="hidden-xs">Пригласить на</span> площадку</span>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid contentUser">
    <div class="container">
<!--         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 forSmall">
            <div class="box contentUserBox col-lg-12 col-md-12 col-sm-12 col-xs-12 shadow">
                <h2 class="h2-black">Площадки <?= $username ?> <span><?= count($courts) ?></span></h2>
                <div class="divider"></div>
                <?php

                    if ($courts){
                        $i=0;
                        foreach ($courts as $court) {
                            echo '<a href=/court/' . $court["id"] . '><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 wrap"><div class="contentUserImg" style="background-image: url(/img/courts/'.$photo[$i]['photo'].')"><p>'. $court["address"] . '</p></div></div></a>';
                            $i++;
                        }
                    }elseif($userAuth != 0)
                    {
                        echo '<p class="noinfo">Здесь будут отображаться площадки, которые ты добавишь в избранные ツ</p>
                <a href="/court" class="mid-green-btn find">Найти площадку</a>';
                    }
                    else
                        echo '<p class="noinfo">Данный игрок ещё не подписан ни на одну площадку ツ</p>'; 
                ?>
            </div>
        </div> -->
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 usersSquares">
            <div class="box usersSquare con col-lg-12 col-md-12 col-sm-12 col-xs-12 shadow">
                <div class="header"><div class="menu">Площадки <?= $username ?><span> <?= count($courts) ?></span></div></div>
                <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xs-12" id="map">
                    Я карта с метками площадок пользователя, если карты нет, то заглушка, она закомменчена снизу
                </div>
<!--                 <div class="noinfo">
                    <i class="fa fa-street-view fa-2x blue" aria-hidden="true"></i>
                    <br><?= $username ?> еще добавил <br class="hidden-xs"> в свой профиль площадки<br>
                </div> -->
            </div>

        </div>
        <div class="gamesWrap col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-5 col-sm-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box games shadow" id="game_list">
                    <div class="header"><div class="menu">Игры <?= $username ?></div></div>
                    <p class="noinfo" style="padding-bottom:15px;">
                        <i class="fa fa-user-times fa-2x green" aria-hidden="true"></i>
                        <br>В ближайшее время <?= $username ?> <br class="hidden-xs">не участвует ни в одной игре
                    </p>
            </div>
        </div>

    </div>
</div>
