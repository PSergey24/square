<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use app\assets\CustomBootstrapAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CourtSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Courts';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/css/searchArena.css',[
        'depends' => [CustomBootstrapAsset::className()]
]);
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

$this->registerJs("
    // sport_type select
    var court_images = [
        'court_img_0.jpg','court_img_1.jpg','court_img_2.jpg','court_img_3.jpg','court_img_4.jpg','court_img_5.jpg',
        'court_img_6.jpg','court_img_7.jpg','court_img_8.jpg','court_img_9.jpg','court_img_10.jpg','court_img_11.jpg',
        'court_img_12.jpg','court_img_13.jpg','court_img_14.jpg','court_img_15.jpg','court_img_16.jpg','court_img_17.jpg',
        'court_img_18.jpg','court_img_19.jpg','court_img_20.jpg','court_img_21.jpg',
    ];
    
    $.ajax({url: '/court/get_points', success: function(result) {
        var sport_type = " . $sport_type . ";
        var visible_val;
        infowindow = new google.maps.InfoWindow({
            content: contentString
        });
        $('#sport_type').val(sport_type);
        $.each(result, function (index, value) {
            var rnd = Math.floor(Math.random() * (20 - 0 + 1)) + 0;
            if(value['type_id'] == sport_type || sport_type == 0)
                visible_val = true;
            else
                visible_val = false;
            if (value['type_id'] == 1) {
                var pinImgLink = '/img/basket.png';
            }else
                var pinImgLink = '/img/foot.png';

            var marker = new google.maps.Marker({
                id: value['id'],
                position: {lat: Number(value['lat']), lng: Number(value['lon'])},
                map: map,
                animation: google.maps.Animation.DROP,
                type_id: value['type_id'],
                address: value['address'],
                photo: court_images[rnd],
                visible: visible_val,
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
                                            '\"><div style=\"background-image: url(/img/' + this.photo + ');\" class=\"image-right image\">' +
                                            '<div class=\"close\"></div><div class=\"players\">' +
                                                '<i class=\"fa fa-male\" aria-hidden=\"true\"></i>25</div><span>Открыть площадку</span></div>' +
                                            '<div class=\"sliderText center \">' + this.address + '</div></a>' +
                                        '</div>' +
                                    '</div>';
                infowindow.setContent(contentString);
                infowindow.open(map, marker);
            });
            markers.push(marker);
        })
    }});
");

$this->registerJs("
     $('#sport_type').change(function (e) {
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].type_id !== $('#sport_type').val()) {
                markers[i].setVisible(false);
            }else {
                markers[i].setVisible(true);
            }
        }
    });
");
$this->registerJs("
     $('#nearest_courts').click(function (e) {
         if (navigator.geolocation) {
           navigator.geolocation.getCurrentPosition(function(position) {
             var pos = {
               lat: position.coords.latitude,
               lng: position.coords.longitude
             };
             infowindow.close();
             if(myloc_marker) {
                 if(myloc_infoWindow) { 
                    myloc_infoWindow.close(); 
                    }
                 myloc_marker.setPosition(pos);
             } else {
                 myloc_marker = new google.maps.Marker({
                     position: pos,
                     map: map,
                     icon: '/img/my_location.png'
                 });
             }
             myloc_infoWindow.open(map, myloc_marker);
             map.setCenter(pos);
             map.setZoom(14);
           }, function() {
               alert(\"Ошибка: в Вашем браузере данная функция недоступна!\");
           });
         } else {
           // Browser doesn't support Geolocation
           alert(\"Ошибка: Ваш браузер не поддерживает геолокацию!\");
         }
    });
");
$this->registerJs("
     $('#district_type').change(function (e) {
        $.ajax({
            url: '/court/district_coord', 
            data: {
                name: $('#district_type :selected').text()
            }, 
            success: function(result) {
                var pos = new google.maps.LatLng(result['lat'], result['lon']);
                map.setCenter(pos);
                map.setZoom(11);
            }
        });
    });
");
?>

<div class="container-fluid" id="center" >

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">
            <form>
                <select id="city_type" class="search">
                    <option value="1" disabled selected>Санкт-Петербург</option>
                </select>
                <select id="district_type" class="search">
                    <option value="" disabled selected>Выберите район</option>
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
                <select id="sport_type" class="search">
                    <option value="0" selected>Вид спорта</option>
                    <option value="1">Баскетбол</option>
                    <option value="2">Футбол</option>
                </select>
            </form>

            <div class="searchImgBox col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div id="map"></div>
            </div>
            <div class="center"><button class="mid-blue-btn" id="nearest_courts">Ближайшие к вам</button><a class="mid-green-btn" href="court/create">Добавить площадку</a></div>
        </div>

</div>

<div class="container-fluid" id="slider">
    <div class="container containerSlider">
        <h2 class="h2-black col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">Популярные площадки</h2>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall forSmall margin">
                <a href="<?= '/court/' . $popular[0]['id'] ?>" id="court_link">
                <div style="background-image: url(../img/court_img_22.jpg);" class="images"><div class="players"><i class="fa fa-male" aria-hidden="true"></i>34</div><span>Открыть площадку</span></div>
                <div class="sliderTextPop"><?= $popular[0]['address'] ?></div></a>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall forSmall margin">
                <a href="<?= '/court/' . $popular[2]['id'] ?>" id="court_link">
                <div style="background-image: url(../img/court_img_23.jpg);" class="images"><div class="players"><i class="fa fa-male" aria-hidden="true"></i>21</div><span>Открыть площадку</span></div>
                <div class="sliderTextPop"><?= $popular[2]['address'] ?></div></a>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall forSmall margin">
                <a href="<?= '/court/' . $popular[1]['id'] ?>" id="court_link">
                <div style="background-image: url(../img/court_img_24.jpg);" class="images"><div class="players"><i class="fa fa-male" aria-hidden="true"></i>25</div><span>Открыть площадку</span></div>
                <div class="sliderTextPop"><?= $popular[1]['address'] ?></div></a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" id="notArena">
    <div class="container">
        <h2 class="h2-white col-lg-12 col-md-12 col-sm-12 col-xs-12">Не нашли площадку?</h2>
        <p class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Хочешь организовать игру на спортивной площадке, а её нет на карте? Не беда! :) Тебе нужно всего лишь отправить нам заявку на её добавление и в ближайшее время она появится на карте.</p>
        <div class="center col-xs-12"><a class="big-green-btn shadow" href="court/create">Добавить площадку</a></div>
    </div>
</div>