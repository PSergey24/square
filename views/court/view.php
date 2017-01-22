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
            zoom: 15,
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
                        if ($('#bookmark span').text() == 'Удалить из избранного') {
                            $('.mid-green-btn i').removeClass('fa-star');
                            $('.mid-green-btn i').addClass('fa-star-o');
                            $('#bookmark span').text('Добавить в избранное');
                        }else {
                            $('.mid-green-btn i').removeClass('fa-star-o');
                            $('.mid-green-btn i').addClass('fa-star');
                            $('#bookmark span').text('Удалить из избранного');  
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

?>

<div class="container-fluid top">
    <div class="container s">
        <h2 class="h2-white" style="position:relative;"><?= $court['address'] ?></h2>
        <div >
            <?php
            if ($court['type_id'] != 1)
                echo Html::a('Футбол', Url::to(['/court', 'sport_type' => 2], true), [
                    'class' => 'tag'
                ]);
            else
                echo Html::a('Баскетбол', Url::to(['/court', 'sport_type' => 1], true), [
                    'class' => 'tag'
                ]);;
            ?>
        </div>
        <div class="description ">
            <a id="description_link" href="javascript:void(0)" title="" rel="nofollow" class="link">Описание
                площадки</a><?= Html::img('@web/img/down.png', ['class' => 'arrow', 'id' => '1']) ?>
            <div class="description-text" id="description" style="display: none">
                <p>Классная площадка, с искусстенным газоном. Есть хорошие баскетбольные кольца. Так же есть 2 беговые
                    дорожки. Ворота без сетки, но игре это особо не мешает.</p>
            </div>
        </div>
        <div class="buttons">
            <a class="mid-green-btn shadow" id="bookmark">
                <?php if ($bookmarked) : ?>
                    <i class="fa fa-star fa-lg" aria-hidden="true"></i>
                    <span class="hidden-xs">Удалить из избранного</span>
                <?php else:?>
                    <i class="fa fa-star-o fa-lg" aria-hidden="true"></i>
                    <span class="hidden-xs">Добавить в избранное</span>
                <?php endif;?>
            </a>
            <button class="mid-blue-btn shadow" id="like">
                <i class="fa fa-heart-o fa-lg" aria-hidden="true"></i>
                <span class="hidden-xs">Мне нравится</span>
                <span class="players"><?= $likes_count ?></span>
            </button>
        </div>

    </div>
</div>


<div class="container">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
        <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 box chat shadow" id="map">

        </div>
    </div>
    <div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
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
            else echo '<p class="nogames">В ближайшее время игр нет :(</p>';
            ?>
            <button class="mid-green-btn" data-toggle="modal" data-target=".bs-example-modal-lg">Создать игру</button>
        </div>
        <div class="social">
            <span>ПОЗОВИ ДРУЗЕЙ НА ПЛОЩАДКУ</span><br> 
            
            <!-- Put this script tag to the <head> of your page -->
            <script type="text/javascript" src="https://vk.com/js/api/share.js?94" charset="windows-1251"></script>
            <!-- Put this script tag to the place, where the Share button will be -->
            <script type="text/javascript">
            document.write(VK.Share.button({url: 'http://kvadrat.local/court/<?= $court['id'] ?>'}, {type: 'custom', text: "<i class=\"fa fa-vk fa-lg\" aria-hidden=\"true\"></i>"})); 
            </script>
            <a href="#"><i class="fa fa-facebook fa-lg" aria-hidden="true"></i></a>

        </div>
        <?php Pjax::end(); ?>

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
