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
                <h1>Раздел в стадии разработки</h1>
    			<div class="game">
                    <div class="description">
                        <p class="adress">Соболевская ул. д 45 (Лучше обходить сбоку)</p>
                        <i class="fa fa-futbol-o" aria-hidden="true" style="color:#F44336;" title="Нужен мяч"></i>
                        <p class="sport">Баскетбол</p>
                        <p class="buttons">
                            <a href="#" class="mid-blue-btn hideXS"> На карте</a>
                            <a href="#" class="mid-blue-btn showXS"> На площадку</a>
                            <a href="#" class="mid-green-btn">Игроков <span>+9</span></a>
                        </p>
                    </div>
                    <div class="time">
                        <div class="align">сегодня<br><span class="date">22:48</span></div>
                    </div>         
                </div>
                <div class="game">
                    <div class="description">
                        <p class="adress">Соболевская ул. д 45 (Лучше обходить сбоку)</p>
                        <i class="fa fa-futbol-o" aria-hidden="true" style="color:#F44336;" title="Нужен мяч"></i>
                        <p class="sport">Баскетбол</p>
                        <p class="buttons">
                            <a href="#" class="mid-blue-btn hideXS"> На карте</a>
                            <a href="#" class="mid-blue-btn showXS"> На площадку</a>
                            <a href="#" class="mid-green-btn">Игроков <span>+9</span></a>
                        </p>
                    </div>
                    <div class="time">
                        <div class="align">сегодня<br><span class="date">22:48</span></div>
                    </div>         
                </div>
                <div class="game">
                    <div class="description">
                        <p class="adress">Соболевская ул. д 45 (Лучше обходить сбоку)</p>
                        <i class="fa fa-futbol-o" aria-hidden="true" style="color:#F44336;" title="Нужен мяч"></i>
                        <p class="sport">Баскетбол</p>
                        <p class="buttons">
                            <a href="#" class="mid-blue-btn hideXS"> На карте</a>
                            <a href="#" class="mid-blue-btn showXS"> На площадку</a>
                            <a href="#" class="mid-green-btn">Игроков <span>+9</span></a>
                        </p>
                    </div>
                    <div class="time">
                        <div class="align">сегодня<br><span class="date">22:48</span></div>
                    </div>         
                </div>
                <div class="game">
                    <div class="description">
                        <p class="adress">Соболевская ул. д 45 (Лучше обходить сбоку)</p>
                        <i class="fa fa-futbol-o" aria-hidden="true" style="color:#F44336;" title="Нужен мяч"></i>
                        <p class="sport">Баскетбол</p>
                        <p class="buttons">
                            <a href="#" class="mid-blue-btn hideXS"> На карте</a>
                            <a href="#" class="mid-blue-btn showXS"> На площадку</a>
                            <a href="#" class="mid-green-btn">Игроков <span>+9</span></a>
                        </p>
                    </div>
                    <div class="time">
                        <div class="align">сегодня<br><span class="date">22:48</span></div>
                    </div>         
                </div>
                <div class="game">
                    <div class="description">
                        <p class="adress">Соболевская ул. д 45 (Лучше обходить сбоку)</p>
                        <i class="fa fa-futbol-o" aria-hidden="true" style="color:#F44336;" title="Нужен мяч"></i>
                        <p class="sport">Баскетбол</p>
                        <p class="buttons">
                            <a href="#" class="mid-blue-btn hideXS"> На карте</a>
                            <a href="#" class="mid-blue-btn showXS"> На площадку</a>
                            <a href="#" class="mid-green-btn">Игроков <span>+9</span></a>
                        </p>
                    </div>
                    <div class="time">
                        <div class="align">сегодня<br><span class="date">22:48</span></div>
                    </div>         
                </div>
                <div class="game">
                    <div class="description">
                        <p class="adress">Соболевская ул. д 45 (Лучше обходить сбоку)</p>
                        <i class="fa fa-futbol-o" aria-hidden="true" style="color:#F44336;" title="Нужен мяч"></i>
                        <p class="sport">Баскетбол</p>
                        <p class="buttons">
                            <a href="#" class="mid-blue-btn hideXS"> На карте</a>
                            <a href="#" class="mid-blue-btn showXS"> На площадку</a>
                            <a href="#" class="mid-green-btn">Игроков <span>+9</span></a>
                        </p>
                    </div>
                    <div class="time">
                        <div class="align">сегодня<br><span class="date">22:48</span></div>
                    </div>         
                </div>
                <div class="game">
                    <div class="description">
                        <p class="adress">Соболевская ул. д 45 (Лучше обходить сбоку)</p>
                        <i class="fa fa-futbol-o" aria-hidden="true" style="color:#F44336;" title="Нужен мяч"></i>
                        <p class="sport">Баскетбол</p>
                        <p class="buttons">
                            <a href="#" class="mid-blue-btn hideXS"> На карте</a>
                            <a href="#" class="mid-blue-btn showXS"> На площадку</a>
                            <a href="#" class="mid-green-btn">Игроков <span>+9</span></a>
                        </p>
                    </div>
                    <div class="time">
                        <div class="align">сегодня<br><span class="date">22:48</span></div>
                    </div>         
                </div>
                <div class="game">
                    <div class="description">
                        <p class="adress">Соболевская ул. д 45 (Лучше обходить сбоку)</p>
                        <i class="fa fa-futbol-o" aria-hidden="true" style="color:#F44336;" title="Нужен мяч"></i>
                        <p class="sport">Баскетбол</p>
                        <p class="buttons">
                            <a href="#" class="mid-blue-btn hideXS"> На карте</a>
                            <a href="#" class="mid-blue-btn showXS"> На площадку</a>
                            <a href="#" class="mid-green-btn">Игроков <span>+9</span></a>
                        </p>
                    </div>
                    <div class="time">
                        <div class="align">сегодня<br><span class="date">22:48</span></div>
                    </div>         
                </div>
                
    		</div>
    		<div class="col-lg-8 col-md-7 col-sm-6 col-xs-12" id="map"></div>
    	</div>
    </div>

