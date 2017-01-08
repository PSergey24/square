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
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="small">
    		<div class="col-lg-6 col-md-5 col-sm-6 col-xs-12 games">
    			<div class="search">
                    <div class="show col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="title show col-lg-2 col-md-2 col-sm-3 col-xs-4">Поиск игр</div>
                        <div class="closest hidden-xs">
                            <span class="word">Ближайшие к вам</span>
                            <i class="fa fa-location-arrow" aria-hidden="true"></i>
                        </div>
                        <div class="openFilters">
                            <span class="word">Фильтры</span>
                            <i class="fa fa-sliders" aria-hidden="true"></i>
                        </div>
                        <div class="closest visible-xs">
                            <span class="word">Ближайшие к вам</span>
                            <i class="fa fa-location-arrow" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="filters">
                        <div class="stroke col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="pre col-lg-2 col-xs-12 col-sm-12 align">Место</div>
                            <div class="col-lg-3 col-xs-6 col-sm-6" id="city">
                                <select >
                                    <option>Санкт-Петербург</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-lg-offset-1 col-xs-6" id="district">
                                <select>
                                    <option>Все районы</option>
                                    <option value="1">Кронштадтский</option>
                                    <option value="2">Адмиралтейский</option>
                                    <option value="3">Василеостровский</option>
                                    <option value="4">Выборгский</option>
                                    <option value="5">Калининский</option>
                                    <option value="6">Кировский</option>
                                    <option value="7">Колпинский</option>
                                    <option value="8">Красногвардейский</option>
                                    <option value="9">Красносельский</option>
                                    <option value="10">Курортный</option>
                                    <option value="11">Московский</option>
                                    <option value="12">Невский</option>
                                    <option value="13">Петроградский</option>
                                    <option value="14">Петродворцовый</option>
                                    <option value="15">Приморский</option>
                                    <option value="16">Пушкинский</option>
                                    <option value="17">Фрунзенский</option>
                                    <option value="18">Центральный</option>
                                </select>
                            </div>
                        </div>
                        <div class="stroke col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="pre col-lg-2 col-xs-12 col-sm-12">Время</div>
                            <div class="col-lg-2 col-xs-4 col-sm-4 gameTime">Сегодня</div>
                            <div class="col-lg-1 col-xs-4 col-sm-4 gameTime">Завтра</div>
                            <div class="col-lg-2 col-lg-offset-1 col-xs-4 col-sm-4 gameTime">Ближайшие</div>
                        </div>
                        <div class="stroke col-lg-12">
                            <div class="pre col-lg-2 col-xs-12 col-sm-12 align">Игра</div>
                            <div class="col-lg-3 col-xs-6 col-sm-6" id="kind">
                                <select>
                                    <option>Вид спорта</option>
                                    <option>Футбол</option>
                                    <option>Баскетбол</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-lg-offset-1 col-xs-6 col-sm-6" id="players">
                                <span class="amount">Игроков</span><input type="number" min="0" max="2" >
                                <span class="">-</span><input type="number" min="0" max="2" >
                            </div>
                        </div>
                        <div class="buttons col-lg-12"><div class="reset">Сбросить</div><button class="mid-green-btn">Применить</button></div>
                    </div>
                </div>
                <div class="game-list">
                    <div class="col-xs-12 col-lg-6 first">
                        <div class="shadow box game-new basketball">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="top">
                                    <div class="square">наб. реки Пряжки д. 4 Литер А</div>
                                    <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true"></i></div>
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
                                    </div>
                                </div>
                            </div>
                            <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="type">
                                    <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                                    <span class="big">басктебол</span>
                                </div>
                                <div class="time">
                                    <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                                    <span class="big">завтра 18:45</span>
                                </div>
                                <div class="ball">
                                    <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                                    <span class="big">есть</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 first">
                        <div class="shadow box game-new basketball">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="top">
                                    <div class="square">наб. реки Пряжки д. 4 Литер А</div>
                                    <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true"></i></div>
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
                                    </div>
                                </div>
                            </div>
                            <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="type">
                                    <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                                    <span class="big">басктебол</span>
                                </div>
                                <div class="time">
                                    <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                                    <span class="big">завтра 18:45</span>
                                </div>
                                <div class="ball">
                                    <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                                    <span class="big">есть</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12    col-lg-6 first">
                        <div class="shadow box game-new football">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="top">
                                    <div class="square">наб. реки Пряжки д. 4 Литер А</div>
                                    <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true"></i></div>
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
                                    </div>
                                </div>
                            </div>
                            <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="type">
                                    <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                                    <span class="big">футбол</span>
                                </div>
                                <div class="time">
                                    <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                                    <span class="big">завтра 18:45</span>
                                </div>
                                <div class="ball">
                                    <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                                    <span class="big">есть</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12    col-lg-6 first">
                        <div class="shadow box game-new">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="top">
                                    <div class="square">наб. реки Пряжки д. 4 Литер А</div>
                                    <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true"></i></div>
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
                                    </div>
                                </div>
                            </div>
                            <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="type">
                                    <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                                    <span class="big">футбол</span>
                                </div>
                                <div class="time">
                                    <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                                    <span class="big">завтра 18:45</span>
                                </div>
                                <div class="ball">
                                    <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                                    <span class="big">есть</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="mid-blue-btn">Еще</button>
        		</div>
            </div>
    		<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12 hidden-xs" id="map"></div>
    	</div>
    </div>

