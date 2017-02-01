<?php

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\models\Profile;

$this->title = 'Личный профиль пользователя. '.$username;
$this->registerCssFile('/css/userProfile.css',[
    'depends' => [AppAsset::className()]
]);
$this->registerCssFile('/css/squareProfile.css',[
    'depends' => [AppAsset::className()]
]);
$this->registerJsFile(
    '@web/js/profile.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

    $this->params['picture_href'] = Profile::getAvatar();


$this->registerJs("
    function people(game,symbol){

        $.ajax({
            type: 'POST',
            url: '/game/player',
            data: 'game='+game+'&&symbol='+symbol,
            success: function(data){
                if(data == 'Вы не авторизованы')
                    alert(data);
                else{
                    var result = data.split('|');
                    if(result[1] == 0)
                    {
                        $('[data-id-game='+result[0]+']').remove();
                        var n = $('[data-num-game]').attr('data-num-game');
                        n = n - 1;
                        $('[data-num-game]').attr('data-num-game',n);
                    }
                    else{
                        $('[data-id-game='+result[0]+'] .circle a').remove();
                        $('[data-id-game='+result[0]+'] .playersMap a').remove();
                        if(result[2] == '-')
                        {
                            $('[data-id-game-plus='+result[0]+'] span').html('+');
                            $('[data-id-game-plus='+result[0]+']').attr('onclick','people('+result[0]+',\'+\')');
                        }
                        else{
                            $('[data-id-game-plus='+result[0]+'] span').html('-');
                            $('[data-id-game-plus='+result[0]+']').attr('onclick','people('+result[0]+',\'-\')');
                        }
                        $('[data-id-game='+result[0]+'] .count').html(result[1]);
                        
                        
                        $('[data-id-game='+result[0]+'] .circle').append(result[3]);
                        
                    }
                }
            },
            error:  function(data){
                    alert('Ошибка: '+data);
            }
        });
    }

", $this::POS_HEAD);

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
                                    echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 wrap" data-court="' . $court["id"] . '">
                                            <a href=/court/' . $court["id"] . '>
                                                <div class="contentUserImg" style="background-image: url(/img/courts/'.$photo[$i]['photo'].')">
                                                    <p>'. $court["address"] . '</p><a href="#" data-court-id="' . $court["id"] . '"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                </div>
                                            </a>
                                        </div>'
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
                    <?php 
                    if(count($games) > 0)
                    {
                        $n = 0;
                        foreach ($games as $game) { $n++;?>
                            <?= $this->render('_game_profile', [
                                'n' => $n,
                                'game' => $game,
                                'users' => $users
                            ]) ?>
                    <?php 
                        }
                    }else{
                    ?>
                    <p class="noinfo">
                        <i class="fa fa-calendar-times-o fa-4x" aria-hidden="true"></i>
                        <br>Пока что игр на твоих площадках нет,<br> так что создай игру сам :)
                    </p>
                    <?php
                    }
                    ?>
                    <a href="/court" class="mid-green-btn find">Создать игру</a>
    <!--                 <p class="noinfo">
                        <i class="fa fa-hand-peace-o fa-4x" aria-hidden="true"></i>
                        <br>Здесь будут отображаться<br> ближайшие игры на твоих площадках
                    </p> -->
                        
                        
                </div>
            </div>
        </div>
    </div>
</div>
