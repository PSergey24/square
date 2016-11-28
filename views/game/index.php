<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Games';
$this->registerCssFile('/css/games.css');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDxkhFJ3y--2AadULGUoE9kdlRH3nT-668&callback=initMap',
    [
        'async' => true,
    ]
);
$this->registerJs("
    var map, myloc_marker, myloc_infoWindow, infowindow, contentString;
    var markers = new Array();
    var directionsDisplay, directionsService, map;
    function initMap() {
        var directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer();
        var latlng = new google.maps.LatLng(59.910326, 30.3185942);
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
        directionsDisplay.setMap(map);
        
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            myloc_marker = new google.maps.Marker({
                position: pos,
                map: map,
                icon: '/img/my_location.png'
            });
            myloc_infoWindow = new google.maps.InfoWindow({
                content: 'Вы находитесь здесь'
            });
            myloc_infoWindow.open(map, myloc_marker);
            map.setCenter(pos);
            map.setZoom(14);
          }, function() {
          });
        } else {
          // Browser doesn't support Geolocation
          alert('Ошибка: Ваш браузер не поддерживает геолокацию!');
        }           
    }
", $this::POS_HEAD);
?>

    <div class="container-fluid info">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall" id="small">
    		<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 games">
    			<div class="search">Поиск игр <a href="#">Ближайшие к вам</a></div>
                <div class="shadow box game-new basketball">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="top">
                            <div class="square">наб. реки Пряжки д. 4 Литер А</div>
                            <div class="onmap">На карте</div>
                        </div>
                        <div id="maps" class="visible-xs"><!--КАРТА ДЛЯ ТЕЛЕФОНА-->
                            
                        </div>
                        <div class="divider"></div>
                        <div class="people">
                            <p>Игроков: <span class="count">7</span></p>
                            <div class="scroll">
                                <div class="right"></div>
                                <div class="circle">
    
                                    <div class="plus man"><span>+</span></div>
                                    <a href="#"><img src="/img/court_img_8.jpg" class="man"></a>
                                    <a href="#"><img src="/img/court_img_5.jpg" class="man"></a>
                                    <a href="#"><img src="/img/court_img_4.jpg" class="man"></a>
                                    <a href="#"><img src="/img/court_img_2.jpg" class="man"></a>
                                    <a href="#"><img src="/img/court_img_1.jpg" class="man"></a>
                                    <a href="#"><img src="/img/court_img_9.jpg" class="man"></a>
                                </div>
                            </diV>
                        </div>
                    </div>
                    <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="type col-xs-3 col-xs-offset-1">
                            <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                            <span class="big">басктебол</span>
                        </div>
                        <div class="time col-xs-4 col-xs-offset-1">
                            <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                            <span class="big">завтра 18:45</span>
                        </div>
                        <div class="ball col-xs-2 col-xs-offset-1">
                            <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                            <span class="big">есть</span>
                        </div>
                    </div>
                </div>
                <div class="shadow box game-new football">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="top">
                            <div class="square">наб. реки Пряжки д. 4 Литер А</div>
                            <div class="onmap">На карте</div>
                        </div>
                        <div id="maps" class="visible-xs"><!--КАРТА ДЛЯ ТЕЛЕФОНА-->
                            
                        </div>
                        <div class="divider"></div>
                        <div class="people">
                            <p>Игроков: <span class="count">3</span></p>
                            <div class="scroll">
                                <div class="right"></div>
                                <div class="circle">
    
                                    <div class="plus man"><span>+</span></div>
                                    <a href="#"><img src="/img/court_img_18.jpg" class="man"></a>
                                    <a href="#"><img src="/img/court_img_15.jpg" class="man"></a>
                                    <a href="#"><img src="/img/court_img_14.jpg" class="man"></a>
                                </div>
                            </diV>
                        </div>
                    </div>
                    <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="type col-xs-3 col-xs-offset-1">
                            <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                            <span class="big">футбол</span>
                        </div>
                        <div class="time col-xs-4 col-xs-offset-1">
                            <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                            <span class="big">завтра 14:30</span>
                        </div>
                        <div class="ball col-xs-2 col-xs-offset-1">
                            <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                            <span class="big">Нет</span>
                        </div>
                    </div>
                </div>
    		</div>
    		<div class="col-lg-8 col-md-7 col-sm-6 col-xs-12 hidden-xs" id="map"></div>
    	</div>
    </div>

