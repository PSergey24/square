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

$this->params['picture_href'] = Profile::getAvatar();

?>

<div class="container-fluid top">
    <div class="container s">

        <div class="top-info">
            <img class="userPhoto" src="<?= $this->params['picture_href']  ?>">

            <h1 class="userName"><?= $username ?></h1>
            <p>
                <?= Html::a('Футбол', Url::to(['/court', 'sport_type' => 2], true), ['class' => 'tag']); ?>
                <?= Html::a('Баскетбол', Url::to(['/court', 'sport_type' => 1], true), ['class' => 'tag']); ?>
            </p>

        </div>
        <div class="options">
            <div class="mid-green-btn"><i class="fa fa-pencil" aria-hidden="true"></i>
                <span class="">Редактировать профиль</span>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid contentUser forSmall">
    <div class="container">
        <div class="row reorder">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 gamesWrap">
                <div class="box contentUserBox col-lg-12 col-md-12 col-sm-12 col-xs-12 shadow">
                    <div class="header"><div class="menu">Мои площадки</div></div>
                    <div class="squares">
                        <?php 
                            if ($courts){
                                $i = 0;
                                foreach ($courts as $court) {
                                    echo '<a href=/court/' . $court["id"] . '>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 wrap">
                                            <div class="contentUserImg" style="background-image: url(/img/courts/'.$photo[$i]['photo'].')">
                                                <p>'. $court["address"] . '</p><a href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </a>'
                                    ;
                                    $i++;
                                }
                                echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 wrap">
                                        <div class="addSquare">
                                            <i class="fa fa fa-futbol-o" aria-hidden="true"></i>
                                            <span>Добавить</span>
                                        </div>
                                    </div>';
                            } else echo '<p class="noinfo"><i class="fa fa-futbol-o fa-4x" aria-hidden="true"></i><br>Добавь себе площадки, на которых ты играешь,<br> чтобы видеть на них ближайшие игры.</p>
                        <a href="/court" class="mid-blue-btn find">Найти площадку</a>';
                        ?>
                    </div>
    <!--                 <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="map">я карта</div>
                    </div> -->
                </div>
            </div>
            <div class="gamesWrap col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-5 col-sm-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box games shadow" id="game_list">
                    <div class="header"><div class="menu">Ближайшие игры</div></div>
                    <p class="noinfo">
                        <i class="fa fa-calendar-times-o fa-4x" aria-hidden="true"></i>
                        <br>Пока что игр на твоих площадках нет,<br> так что создай игру сам :)
                    </p>
                    <a href="/court" class="mid-green-btn find">Создать игру</a>
    <!--                 <p class="noinfo">
                        <i class="fa fa-hand-peace-o fa-4x" aria-hidden="true"></i>
                        <br>Здесь будут отображаться<br> ближайшие игры на твоих площадках
                    </p> -->
                        
    <!--                     <div class="game">
                            <div class="gameTop">
                                <span class="number">1.</span>
                                <span class="time">Сегодня, 18:45</span>
                                <div class="social">
                                    <a href="#"><i class="fa fa-vk" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="people">
                                <p>Игроков:<span class="count"> 2</span></p>
                                <div class="scroll">
                                    <div class="right"></div>
                                    <div class="circle">
                                        <div class="plus man"><span>+</span></div>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="bottom">
                                <div class="gameType">Игра:<span>Футбол</span></div>
                                <div class="ballType">Мяч:<span>Есть</span></div>
                                <a href="#"><span>Большой Казачий пер., уч. 2</span></a>
                            </div>
                        </div>
                        <div class="game">
                            <div class="gameTop">
                                <span class="number">1.</span>
                                <span class="time">Сегодня, 18:45</span>
                                <div class="social">
                                    <a href="#"><i class="fa fa-vk" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="people">
                                <p>Игроков:<span class="count"> 2</span></p>
                                <div class="scroll">
                                    <div class="right"></div>
                                    <div class="circle">
                                        <div class="plus man"><span>+</span></div>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                        <a href="#"><img src="/img/uploads/nick.jpg" class="man"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="bottom">
                                <div class="gameType">Игра:<span>Футбол</span></div>
                                <div class="ballType">Мяч:<span>Есть</span></div>
                                <a href="#"><span>Большой Казачий пер., уч. 2</span></a>
                            </div>
                        </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
