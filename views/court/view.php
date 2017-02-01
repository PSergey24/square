<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Court */
/* @var $model_form_game_create app\models\forms\GameCreateForm */

use app\assets\AppAsset;
use yii\widgets\Pjax;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Courts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/css/squareProfile.css', [
    'depends' => [AppAsset::className()]
]);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDxkhFJ3y--2AadULGUoE9kdlRH3nT-668&callback=initMap',
    [
        'async' => true,
    ]
);
$this->registerJs("
    var map;
    var court = " . $court_json . ";
    function initMap() {
        var latlng = new google.maps.LatLng(court.lat, court.lon);
        var options = {
            zoom: 11,
            center: latlng,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoomControl: true,
            scaleControl: true
        };

        map = new google.maps.Map(document.getElementById('map'), options);

        if (court.type_id == 1) {
            var pinImgLink = '/img/basket.png';
        }else
            var pinImgLink = '/img/foot.png';

        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            animation: google.maps.Animation.DROP,
            icon: pinImgLink
        });
    }
", $this::POS_HEAD);

$this->registerJsFile(
    '@web/js/courtView.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

if (!Yii::$app->user->getIsGuest()) {
    $this->registerJs("
        //Bookmark btn onclick change pic and text
        $('#bookmark').click(function () {
                var url = document.URL;
                var id = url.substring(url.lastIndexOf('/') + 1);
                $.ajax({
                    url: '/court/bookmark',
                    data: {court_id: id},
                    success: function(success) {
                        if ($('#bookmark span').text() == 'Удалить площадку') {
                            $('#bookmark  i').removeClass('fa-star');
                            $('#bookmark  i').addClass('fa-star-o');
                            $('#bookmark span').text('Добавить площадку');
                        }else {
                            $('#bookmark  i').removeClass('fa-star-o');
                            $('#bookmark  i').addClass('fa-star');
                            $('#bookmark span').text('Удалить площадку');  
                        }
                        
                    },
                });
            });
    ");

    $this->registerJs("
        $('#like').click(function() {
            //get current like count in 10 mathematical numeral system
            current_like_count = parseInt($('#like .players').text(), 10);
            
            if ($('#like i').hasClass('fa-heart-o')) {
                $.ajax({
                    url: '/like/create',
                    method: 'POST',
                    data: {court_id: " . $court["id"] . ", user_id:" . Yii::$app->user->getId() . "},
                    success: function(isAdd) {
                        if (isAdd) {
                            $('#like i').removeClass('fa-heart-o');
                            $('#like i').addClass('fa-heart');
                            $('#like .players').text(current_like_count + 1);
                        }
                    },
                });
            }else {
                $.ajax({
                    url: '/like/delete',
                    method: 'DELETE',
                    data: {court_id: " . $court["id"] . ", user_id:" . Yii::$app->user->getId() . "},
                    success: function(isRemoved) {
                        if (isRemoved) {
                            $('#like i').removeClass('fa-heart');
                            $('#like i').addClass('fa-heart-o');
                            $('#like .players').text(current_like_count - 1);
                        }
                    },
                });              
            }      
        });
    ");
    $this->registerJs("
        $.ajax({
            url: '/like/has-like',
            method: 'POST',
            data: {court_id: " . $court["id"] . ", user_id:" . Yii::$app->user->getId() . "},
            success: function(hasLike) {
                if (hasLike) {
                    $('#like i').removeClass('fa-heart-o');
                    $('#like i').addClass('fa-heart');
                }
            },
        });
    ");
} else {
    $this->registerJs("
        $('#like, #bookmark').click(function() {
          $('.needLogin').modal({
               show: true, 
               backdrop: 'static',
               keyboard: true
           })
        });
    ");
}

$this->registerJs("
    function plus(idGame,symbol){
    // $('.join').click(function() {
        // var idGame = $(this).attr('data-id-game'); 
        // var symbol = $('[data-id-game = '+idGame+'] .symbol').html();   

            $.ajax({
              type: \"POST\",
              url: \"/court/button_plus\",
              data: \"id=\"+idGame+\"&&symbol=\"+symbol,
              success: function(data){
                var result = data.split('|');
                if(result[2] != 0)
                {
                    $('[data-block-game = '+result[0]+'] .symbol').html(result[1]);
                    $('[data-block-game = '+result[0]+'] .players').html(result[2]);
                }else{

                    $('[data-block-game = '+result[0]+']').remove();
                    var a = $('#game_list').find('.game');
                    // alert(a[0]);
                    if(!a[0])
                        $('#game_list').html('<p class=\"nogames\">В ближайшее время игр нет :(</p><button class=\"mid-green-btn\" data-toggle=\"modal\" data-target=\".bs-example-modal-lg\">Создать игру</button>');

                }
                
                

              }
            });
    // });
    }
", $this::POS_HEAD);
//Description link on click smoothly fade in description block
$this->registerJs("$('#description_link').click(function () {
        $('#description').toggle(300);
    });");
//refresh games block after create new game
$this->registerJs('$("#game-create").on("pjax:end", function() {
           $.pjax.reload({container: "#games"});
       });');
$this->registerJs("
    function collapsElement(id) {
        if (document.getElementsByClassName(id)[0].style.display != \"none\") {
            document.getElementsByClassName(id)[0].style.display = 'none';
            document.getElementsByClassName('link')[0].style.color = 'rgba(255,255,255,0.7)';
            document.getElementById('1').src = '/img/down.png';
            document.getElementById('1').style.opacity = '0.7';
        }
        else {
            document.getElementsByClassName(id)[0].style.display = '';
            document.getElementsByClassName('link')[0].style.color = 'rgba(255,255,255,1)';
            document.getElementById('1').src = '/img/up.png';
            document.getElementById('1').style.opacity = '1';
        }
    }
");

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
    <div class="container">
        <h2 class="name visible-xs col-xs-12" style="position:relative;"><?= $court['address'] ?>
        <i class="fa fa-exclamation-triangle report" data-toggle="modal" data-target="#modal-report" aria-hidden="true"></i></h2>
        <div class="visible-xs col-xs-12">
            <a href="/court" class="tag"><?= $courtSport['name'] ?></a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xs-12 shadow" id="map"></div>
        </div>
        <h2 class="name visible-sm col-sm-6" style="position:relative;"><?= $court['address'] ?>
        <i class="fa fa-exclamation-triangle report" data-toggle="modal" data-target="#modal-report" aria-hidden="true"></i></h2>
        <div class="visible-sm col-sm-6">
            <a href="/court" class="tag"><?= $courtSport['name'] ?></a>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 wrapper">
            <h2 class="name col-lg-12 col-md-12 col-xs-12 hidden-sm hidden-xs" style="position:relative;"><?= $court['address'] ?>
                <i class="fa fa-exclamation-triangle report" data-toggle="modal" data-target="#modal-report" aria-hidden="true"></i></h2>
            <div class="col-lg-12 col-md-12 col-xs-12 hidden-sm hidden-xs">
                <a href="/court" class="tag"><?= $courtSport['name'] ?></a>
            </div>
            <div class="absolute col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0px 2px !important;">
                <div class="buttons col-lg-12 col-md-12 col-sm-12 col-xs-12 shadow">
                    <div class="menu">
                        <div class="item" id="bookmark">
                            <?php if ($bookmarked) : ?>
                                <i class="fa fa-star fa-lg" aria-hidden="true"></i>
                                <span class="hidden-xs">Удалить площадку</span>
                            <?php else:?>
                                <i class="fa fa-star-o fa-lg" aria-hidden="true"></i>
                                <span class="hidden-xs">Добавить площадку</span>
                            <?php endif;?>
                        </div>
                        <div class="item" id="like">
                            <i class="fa fa-heart-o fa-lg" aria-hidden="true"></i><span class="players"><?= $likes_count ?></span>
                        </div>
                        <div class="item selected tab" data-tab="1">
                            <span>Площадка</span>
                        </div>
                        <div class="item tab" data-tab="2">
                            <span>Описание</span>
                        </div>
                        <div class="social">
                            <span class="hidden-xs">Позови друзей:</span>
                            <a href="#"><i class="fa fa-vk" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

    
<div class="container tab-hidden" id="tab2">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 description">
        <h3>Краткое описание</h3>
        <?php
            if($court['description'] != NULL)
                echo '<p>'.$court['description'].'</p>';
            else
                echo '<p>Описание у данной площадки отсутствует. Вы можете его написать.</p>'
            
        ?>
        <h3>Адрес</h3>
        <p class="address"><?= $court['address'] ?></p>
        <span class="red"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Редактировать площадку</span>
    </div>

    
    <h3 class="col-lg-8 col-md-8 col-sm-8 col-xs-12 photoName">Фотографии с площадок 
        <span class="add"><i class="fa fa-camera" aria-hidden="true"></i>Добавить</span>
        <div class="clear"></div>
    </h3>
    <div class="photos col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <?php
        if(count($courtPhoto) > 0)
        {
            foreach ($courtPhoto as $photo) {
                echo '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6"><div style="background-image: url(/img/courts/'.$photo['photo'].');" class="photo"></div></div>';
           }
        }else{
            echo '<p>Фотографии на данной площадке отсутсвуют. Стань первым, кто их добавит!</p>';
        }
        ?>
        <!-- <div class="more">Еще...</div>       -->
    </div>
</div>

<div class="container" id="tab1">
    <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12 chatWrap">
        <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 box chat shadow">
            <div class="header"><div class="menu">Чат площадки</div></div>
        </div>
    </div>
<!--     <div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
        <h2 class="h2-box">Ближайшие игры</h2>
        <?php Pjax::begin(['enablePushState' => false, 'id' => 'games']); ?>
        <div class="col-lg-12 col-xs-12 box games shadow" id="game_list">
            
        <?php
            if($games) {
                foreach ($games as $game) {
                    echo '<div class="game" data-block-game="'.$game['id'].'">
                        <div class="time">';
                    $tm = strtotime($game['time']);
                    $current_datetime = new DateTime();
                    $current_datetime = date_format($current_datetime, 'Y-m-d');
                    $tm_current = strtotime($current_datetime);
                    if (date("d", $tm) == date("d", $tm_current))
                        echo 'Сегодня ' . date("H:i", $tm);
                    elseif(date("d", $tm) == date(date("d")+1, $tm_current))
                        echo 'Завтра ' . date("H:i", $tm);
                    else
                        echo date("d.m.Y", $tm) ." ". date("H:i", $tm);

                    echo '</div>';
                    if (!$game['need_ball'] == 1)
                        echo '<i class="fa fa-futbol-o" aria-hidden="true" style="color:#F44336;" title="Нужен мяч"></i>';
                    else
                        echo '<i class="fa fa-futbol-o" aria-hidden="true" style="color:#4CAF50;" title="Мяч есть"></i>';
                    echo '<button class="mid-blue-btn" onclick="plus('.$game['id'].',\''.$game['plus'].'\')" data-id-game="'.$game['id'].'"> <span class="symbol">'.$game['plus'].'</span> <span class="players">'.$game['count'].'</span></button></div>';
                }
            }
        ?>  Это нужно было удалить-->


    <div class="gamesWrap">
        <div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-5 col-sm-5 col-xs-12 box games shadow" id="game_list">
            <div class="header"><div class="menu">Ближайшие игры</div></div>
                    <?php 
                    foreach ($games as $i => $game) { $i++; ?>
                        <?= $this->render('_game', [
                            'i' => $i,
                            'game' => $game,
                            'users' => $users
                        ]) ?>
                    <?php }  ?>
            <button class="mid-green-btn" data-toggle="modal" data-target=".bs-example-modal-lg">Создать игру</button>
        </div>

       <!--  <?php Pjax::end(); ?> Это тоже удвлть-->
    </div>
</div>

<div class="modal fade" id="modal-report">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <p class="modal-title">Жалоба на площадку </p>
            </div>
            <div class="modal-body">
                <?= $this->render('_report', [
                    'modelReport' => $modelReport,
                    'id' => $id
                ]) ?>
            </div>
        </div>
    </div>
</div>


<?php if (Yii::$app->user->isGuest): ?>
    <div class="modal fade bs-example-modal-lg needLogin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-sm ">
            <div class="modal-content game-create create-game">
                <i class="fa fa-times close fa-lg" aria-hidden="true" data-dismiss="modal" ></i>
                <a href="/login"><i class="fa fa-sign-out fa-lg login fa-4x" aria-hidden="true"></a></i>
                <p id="warning">Чтобы выполнить это действие вам нужно <a href="/login">авторизоваться</a>.</p>
            </div>

        </div>
    </div>
<?php else: ?>
    <?= $this->render('/game/forms/_form_create', ['model' => $model_form_game_create]) ?>
<?php endif; ?>
