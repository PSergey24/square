<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
$par = require('../config/params.php');

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Games';
$this->registerCssFile('/css/games.css');
$this->params['breadcrumbs'][] = $this->title;

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

        

        $.ajax({url: '/game/get_markers', success: function(result) {
            var res = '".$gameArray."';
            var gameId = res.split(' ');

            infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            $.each(result, function (index, value) {



                if(value['sport_type_id'] == 1){
                    var pinImgLink = '/img/basket.png';
                    var styleItem = 'basketball';
                }else if((value['sport_type_id'] == 2)){
                    var pinImgLink = '/img/foot.png';
                    var styleItem = 'football';
                }
                else if((value['sport_type_id'] == 3)){
                    var pinImgLink = '/img/volleyball.png';
                    var styleItem = '';
                }else{
                    var pinImgLink = '/img/other.png';
                    var styleItem = '';
                }

        
            visible_val = false;

            for(var i = 0; i < gameId.length; i++) {
                if(value['gameId'] == gameId[i])
                    visible_val = true;

            }

                var marker = new google.maps.Marker({
                    id: value['gameId'],
                    position: {lat: Number(value['lat']), lng: Number(value['lon'])},
                    map: map,
                    animation: google.maps.Animation.DROP,
                    style: styleItem,
                    time: value['time'],
                    count: value['count'],
                    people: value['string'],
                    plus: value['plus'],
                    court: value['court_id'],
                    visible: visible_val,
                    icon: pinImgLink
                });

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.close();
                    if(myloc_infoWindow) { 
                        myloc_infoWindow.close(); 
                    }
                    var contentString = '<div class=\"gameMap '+ this.style +'\" data-id-game=\"'+this.id+'\">' +
                                        '<div class=\"timeMap\">'+ this.time +'</div>' + 
                                        '<p>Игроков: <span class=\"count\">'+ this.count+'</span></p>' + 
                                        '<div class=\"scroll\">' +
                                        '<div class=\"playersMap\">' + 
                                        '<div class=\"right\"></div>' +
                                        '<div class=\"plus man\"  data-id-game-plus=\"'+this.id+'\" onclick=\"people('+this.id+',\''+this.plus+'\')\" ><span>'+this.plus+'</span></div>' + this.people +
                                        '</div>' +
                                        '</div>' +
                                        '<p><a href=\"/court/'+this.court+'\" target=\"_blank\"><button class=\"mid-green-btn\">Открыть площадку </button></a></p>' + 
                                        '</div>';
                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                });




                markers.push(marker);
            })

        }});

    }
", $this::POS_HEAD);



$this->registerJsFile(
    '@web/js/game.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);


$this->registerJs("
    function setMapCenter(idGame){
        $.each(markers, function (index, value) {
            if(value['id'] == idGame){
                    var contentString = '<div class=\"gameMap '+ this.style +'\" data-id-game=\"'+this.id+'\">' +
                                        '<div class=\"timeMap\">'+ this.time +'</div>' + 
                                        '<p>Игроков: <span class=\"count\">'+ this.count+'</span></p>' + 
                                        '<div class=\"scroll\">' +
                                        '<div class=\"playersMap\">' + 
                                        '<div class=\"right\"></div>' +
                                        '<div class=\"plus man\"  data-id-game-plus=\"'+this.id+'\" onclick=\"people('+this.id+',\''+this.plus+'\')\" ><span>'+this.plus+'</span></div>' + this.people +
                                        '</div>' +
                                        '</div>' +
                                        '<p><a href=\"/court/'+this.court+'\" target=\"_blank\"><button class=\"mid-green-btn\">Открыть площадку </button></a></p>' + 
                                        '</div>';
                    infowindow.setContent(contentString);

                infowindow.open(map, markers[index]);
            }
        })

        $.ajax({
            type: 'POST',
            url: '/game/pos_game',
            data: 'id='+idGame,
            success: function(data){
                var lat = data['lat'];
                var lng = data['lon'];
                var zoom = 13;
                var pos = new google.maps.LatLng(lat, lng);
                    map.setCenter(pos);
                    map.setZoom(zoom);
            },
            error:  function(data){
                    alert('Ошибка: '+data);
            }
        });
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
                        var n = $('[data-num-game]').attr('data-num-game');
                        n = n - 1;
                        $('[data-num-game]').attr('data-num-game',n);

                        $.each(markers, function (index, value) {
                            if(value['id'] == result[0])
                                markers[index].setVisible(false);
                        })

                    }
                    else{
                        $('[data-id-game='+result[0]+'] .circle a').remove();
                        $('[data-id-game='+result[0]+'] .playersMap a').remove();
                        if(result[2] == '-')
                        {
                            $('[data-id-game-plus='+result[0]+'] span').html('+');
                            $('[data-id-game-plus='+result[0]+']').attr('onclick','people('+result[0]+',\'+\')');
                            $.each(markers, function (index, value) {
                                if(value['id'] == result[0]){
                                    markers[index]['count'] = result[1];
                                    markers[index]['people'] = result[3];
                                    markers[index]['plus'] = '+';
                                }
                            })
                        }
                        else{
                            $('[data-id-game-plus='+result[0]+'] span').html('-');
                            $('[data-id-game-plus='+result[0]+']').attr('onclick','people('+result[0]+',\'-\')');
                            $.each(markers, function (index, value) {
                                if(value['id'] == result[0]){
                                    markers[index]['count'] = result[1];
                                    markers[index]['people'] = result[3];
                                    markers[index]['plus'] = '-';
                                }
                            })
                        }
                        $('[data-id-game='+result[0]+'] .count').html(result[1]);
                        
                        
                        $('[data-id-game='+result[0]+'] .circle').append(result[3]);
                        $('[data-id-game='+result[0]+'] .playersMap').append(result[3]);
                        
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

    <div class="container-fluid info">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="small">
    		<div class="col-lg-6 col-md-5 col-sm-6 col-xs-12 games">
    			<div class="search">
                    <div class="show col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="title show col-lg-2 col-md-2 col-sm-3 col-xs-4">Поиск игр</div>
                        <div class="closest hidden-xs">
                            <span class="word" id="near">Ближайшие к вам</span>
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
                                    <option value="0">Все районы</option>
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
                                <span id="errorPeople"></span>
                            </div>
                        </div>
                        <div class="buttons col-lg-12"><div class="reset">Сбросить</div><button class="mid-green-btn" id="toApply">Применить</button></div>
                    </div>
                </div>
                <div class="game-list">

                    <?php $i = 0;
                    foreach ($listGame as $listGame) { ?>
                        <?= $this->render('_card', [
                            'listGame' => $listGame,
                            'i' => $i,
                            'countUsersArr' => $countUsersArr,
                            'plusMan' => $plusMan,
                            'idUsersArr' => $idUsersArr,
                            'nameSportArr' => $nameSportArr,
                            'nameAreaArr' => $nameAreaArr,
                            'pictureUsersArr' => $pictureUsersArr
                        ]) ?>
                    <?php $i++; }  ?>
                    
        		</div>
                <button class="mid-blue-btn" id="more" data-near="no" data-district="no" data-people="no" data-time="no" data-sport="no" data-num-game="<?= $numGame ?>">Еще</button>
            </div>
    		<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12 hidden-xs" id="map"></div>
    	</div>
    </div>

