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

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDxkhFJ3y--2AadULGUoE9kdlRH3nT-668&callback=initMap',
    [
        'async' => true,
        'defer' => true
    ]
);
$this->registerJs("
    var map, myloc_marker, myloc_infoWindow, infowindow, contentString;
    var markers = new Array();
    var SPB_lat = 59.910326;
    var SPB_lng = 30.3185942;
    var directionsDisplay, directionsService, map;
    function initMap() {
        var directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer();
        var latlng = new google.maps.LatLng(SPB_lat, SPB_lng);
        var options = {
            zoom: 9,
            center: latlng,
            mapTypeControl: false,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoomControl: true,
            scaleControl: false,
            streetViewControl:false
        };

        map = new google.maps.Map(document.getElementById('map'), options);
        directionsDisplay.setMap(map);

        
            infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            var result = ".json_encode($courts).";
            var photo = ".json_encode($photo).";

            $.each(result, function (index, value) {
                
                if(value['type_id'] == 1){
                    var pinImgLink = '/img/basket.png';
                    var styleItem = 'basketball';
                }else if((value['type_id'] == 2)){
                    var pinImgLink = '/img/foot.png';
                    var styleItem = 'football';
                }
                else if((value['type_id'] == 3)){
                    var pinImgLink = '/img/volleyball.png';
                    var styleItem = 'volleyball';
                }else{
                    var pinImgLink = '/img/other.png';
                    var styleItem = '';
                }

        
                var marker = new google.maps.Marker({
                    id: value['id'],
                    position: {lat: Number(value['lat']), lng: Number(value['lon'])},
                    map: map,
                    animation: google.maps.Animation.DROP,
                    style: styleItem,
                    court: value['court_id'],
                    address: value['address'],
                    visible: true,
                    photo: photo[index]['photo'],
                    bookmark: value['count'],
                    icon: pinImgLink
                });

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.close();
                    if(myloc_infoWindow) { 
                        myloc_infoWindow.close(); 
                    }
                    var contentString = '<div class=\"searchImgForm court_info\">' +
                                            '<div class=\"forSmall\">' +
                                                '<a href=\"/court/' + this.id + 
                                                '\"><div style=\"background-image: url(/img/courts/' + this.photo + ');\" class=\"image-right image\">' +
                                                '<div class=\"close\"></div><div class=\"players\">' +
                                                    '<i class=\"fa fa-male\" aria-hidden=\"true\"></i>'+ this.bookmark +'</div><span>Открыть площадку</span></div>' +
                                                '<div class=\"sliderText center \">' + this.address + '</div></a>' +
                                            '</div>' +
                                        '</div>';


// var contentString = '<div class=\"searchImgForm court_info\">' +

//                                         '</div>';

                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                });




                markers.push(marker);
            })

    
    }
", $this::POS_HEAD);

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
                        $.pjax.reload({container: \"#gamesPjax\"});
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
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 usersSquares">
            <div class="box usersSquare con col-lg-12 col-md-12 col-sm-12 col-xs-12 shadow">
                <div class="header"><div class="menu">Площадки <?= $username ?><span> <?= count($courts) ?></span></div></div>
                <?php 
                if(count($courts) > 0)
                {
                ?>
                <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xs-12" id="map"></div>
                <?php 
                }else{
                ?>
                <div class="noinfo">
                    <i class="fa fa-street-view fa-2x blue" aria-hidden="true"></i>
                    <br><?= $username ?> еще добавил <br class="hidden-xs"> в свой профиль площадки<br>
                </div>
                <?php 
                }
                ?>
            </div>

        </div>
        <div class="gamesWrap col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-5 col-sm-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box games shadow" id="game_list">
                    <div class="header"><div class="menu">Игры <?= $username ?></div></div>
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
                    <p class="noinfo" style="padding-bottom:15px;">
                        <i class="fa fa-user-times fa-2x green" aria-hidden="true"></i>
                        <br>В ближайшее время <?= $username ?> <br class="hidden-xs">не участвует ни в одной игре
                    </p>
                    <?php 
                    }
                    ?>
            </div>
        </div>

    </div>
</div>
