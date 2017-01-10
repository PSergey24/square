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
$this->registerJsFile(
    '@web/js/game.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
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
                        </div>
                        <div class="stroke col-lg-12">
                            <div class="pre col-lg-2 col-xs-12 col-sm-12 align">Игра</div>
                            <div class="col-lg-3 col-xs-6 col-sm-6" id="kind">
                                <select id="sportList">
                                    <option value="0" id="default">Вид спорта</option>
                                    <option value="2">Футбол</option>
                                    <option value="1">Баскетбол</option>
                                    <option value="3">Волейбол</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-lg-offset-1 col-xs-6 col-sm-6" id="players">
                                <span class="amount">Игроков</span><input id="min" type="number" min="0" max="2" >
                                <span class="">-</span><input id="max" type="number" min="0" max="2" size="2" >
                            </div>
                        </div>
                        <div class="buttons col-lg-12"><div class="reset">Сбросить</div><button class="mid-green-btn" id="toApply">Применить</button></div>
                    </div>
                </div>
                <div class="game-list">
                    <?php $i = 0;
                    foreach ($listGame as $listGame) { ?>

                        <div class="col-xs-12 col-lg-6 first">
                            <div class=<?php if($listGame['sport_type_id'] == 1) echo '"shadow box game-new basketball"'; elseif($listGame['sport_type_id'] == 2) echo '"shadow box game-new football"'; else echo '"shadow box game-new"'; ?> >
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="top">
                                        <div class="square"><?php echo $nameAreaArr[$i]; ?></div>
                                        <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true"></i></div>
                                    </div>
                                    <div id="maps" class="visible-xs"><!--КАРТА ДЛЯ ТЕЛЕФОНА-->
                                        
                                    </div>
                                    <div class="divider"></div>
                                    <div class="people">
                                        <p>Игроков: <span class="count"><?php echo $countUsersArr[$i]; ?></span></p>
                                        <div class="scroll">
                                            <div class="right"></div>
                                            <div class="circle">
                
                                                <div class="plus man"><span>+</span></div>
                                                <?php
                                                    for($j=0;$j<$countUsersArr[$i];$j++)
                                                    {
                                                        // коммент не удаляем. в этой переменной id пользователя для ссылки
                                                        // echo '<a href="#">'.$idUsersArr[$i][$j]['user_id'].'</a>';
                                                        echo '<a href="#"><img src="/img/uploads/'.$pictureUsersArr[$i][$j]['picture'].'" class="man"></a>';
                                                    }

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="type">
                                        <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                                        <span class="big"><?php echo $nameSportArr[$i]; ?></span>
                                    </div>
                                    <div class="time">
                                        <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                                        <span class="big"><?php 
                                        if(date_format(date_create($listGame['time']), 'd') == (date("d")+1))
                                            echo 'завтра '.date_format(date_create($listGame['time']), 'H:i');
                                        elseif(date_format(date_create($listGame['time']), 'd') == (date("d")))
                                            echo 'сегодня '.date_format(date_create($listGame['time']), 'H:i');
                                        else
                                            echo date_format(date_create($listGame['time']), 'd-m H:i');
  ?></span>
                                    </div>
                                    <div class="ball">
                                        <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                                        <span class="big"><?php if($listGame['need_ball'] == 1) echo 'есть'; else echo 'нет'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php $i++; }  ?>
                    
        		</div>
                <button class="mid-blue-btn" id="more" data-people="no" data-time="no" data-sport="no" data-num-game="<?= $numGame ?>">Еще</button>
            </div>
    		<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12 hidden-xs" id="map"></div>
    	</div>
    </div>

