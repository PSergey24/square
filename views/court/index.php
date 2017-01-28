<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use app\assets\CustomBootstrapAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CourtSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Площадки';
$this->registerCssFile('/css/searchArena.css',[
        'depends' => [CustomBootstrapAsset::className()]
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
    }
", $this::POS_HEAD);
$this->registerJs("
    // sport_type select

    $.ajax({url: '/court/get_points', success: function(result) {
        var sport_type = " . (($filters['sport_type'] != null) ? $filters['sport_type'] : 0) . ";
        var visible_val;
        infowindow = new google.maps.InfoWindow({
            content: contentString
        });
        
        $.each(result, function (index, value) {
            var rnd = Math.floor(Math.random() * (20 - 0 + 1)) + 0;
            if(value['type_id'] == sport_type || sport_type == 0)
                visible_val = true;
            else
                visible_val = false;
            if (value['type_id'] == 1) {
                var pinImgLink = '/img/basket.png';
            }else if(value['type_id'] == 2)
                var pinImgLink = '/img/foot.png';
            else if(value['type_id'] == 3)
                var pinImgLink = '/img/volleyball.png';
            else{
                var pinImgLink = '/img/other.png';
            }

            if(value['photo'] == 0)
                var photoCourt = 'defaultCourt.png';
            else
                var photoCourt = value['photo'];

            var marker = new google.maps.Marker({
                id: value['id'],
                position: {lat: Number(value['lat']), lng: Number(value['lon'])},
                map: map,
                animation: google.maps.Animation.DROP,
                type_id: value['type_id'],
                address: value['address'],
                photo: photoCourt,
                bookmark: value['bookmark'],
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
                                            '\"><div style=\"background-image: url(/img/courts/' + this.photo + ');\" class=\"image-right image\">' +
                                            '<div class=\"close\"></div><div class=\"players\">' +
                                                '<i class=\"fa fa-male\" aria-hidden=\"true\"></i>'+this.bookmark+'</div><span>Открыть площадку</span></div>' +
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
        var selectedValue = $('#sport_type').val() != '' ? $('#sport_type').val() : 0;
        for (var i = 0; i < markers.length; i++) {
            if (selectedValue == 0)
                markers[i].setVisible(true);
            else {
                if (markers[i].type_id != selectedValue) {
                    markers[i].setVisible(false);
                }else {
                    markers[i].setVisible(true);
                }
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
                myloc_marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    icon: '/img/my_location.png'
                });
                myloc_infoWindow = new google.maps.InfoWindow({
                    content: 'Вы находитесь здесь'
                });
                if(myloc_marker) {
                    if(myloc_infoWindow) { 
                        myloc_infoWindow.close(); 
                    }
                    myloc_marker.setPosition(pos);
                }else {
                    myloc_marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        icon: '/img/my_location.png'
                    });
                }
                myloc_infoWindow.open(map, myloc_marker);
                map.setCenter(pos);
                map.setZoom(15);
            }, function() {
                    alert(\"Ошибка: в Вашем браузере данная функция недоступна!\");
                }
            );
         } else {
           // Browser doesn't support Geolocation
           alert(\"Ошибка: Ваш браузер не поддерживает геолокацию!\");
         }
    });
");
$this->registerJs("
     changeDistrict();
     $('#district_type').change(changeDistrict);

     function changeDistrict() {
        if ($('#district_type :selected').val() != '') {
            $.ajax({
            url: '/court/district_coord', 
            data: {
                name: $('#district_type :selected').text()
            }, 
            success: function(result) {
                setMapCenter(result['lat'], result['lng']);
            }
        });
        }else {
            setMapCenter(SPB_lat, SPB_lng, 11);          
        }      
    }
    
    function setMapCenter(lat, lng, zoom = 13) {
        var pos = new google.maps.LatLng(lat, lng);
            map.setCenter(pos);
            map.setZoom(zoom);
    }
", $this::POS_LOAD);
?>

<div class="container-fluid" id="center">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">
        <?php $form = ActiveForm::begin([
            'action' => Url::to(['/court']),
            'fieldConfig' => [
                'options' => [
                    'tag' => false
                ],
                'errorOptions' => ['tag' => false]
            ],
            'enableClientValidation' => false
        ]);
        ?>

        <?= $form->field($filters, 'city')
            ->dropDownList(['Санкт-Петербург'], [
                'class' => 'search selectpicker',
                'id' => 'city_type'
            ])->label(false);
        ?>

        <?php
            if($districts != null){
                echo $form->field($filters, 'district_sity')
                    ->dropDownList($districts, [
                        'id' => 'district_type',
                        'class' => 'search selectpicker',
                        'prompt' => 'Выберите район'
                    ])
                    ->label(false);
            }
        ?>

        <?php  if ($sport_types != null) {
                echo $form->field($filters, 'sport_type')
                    ->dropDownList($sport_types, [
                        'id' => 'sport_type',
                        'class' => 'search selectpicker',
                        'prompt' => 'Вид спорта'
                    ])
                    ->label(false);
            }
        ?>

        <?php ActiveForm::end(); ?>

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
                <a href="<?= empty($popular[1]['id']) ? "/court" : "/court/".$popular[1]['id']?>" id="court_link">
                <div style="background-image: url('../img/courts/<?= empty($img[1]['photo']) ? "" : $img[1]['photo'] ?>');" class="images"><div class="players"><i class="fa fa-male" aria-hidden="true"></i><?= empty($popular[1]['count']) ? "" : $popular[1]['count'] ?></div><span>Открыть площадку</span></div>
                <div class="sliderTextPop"><?= empty($popular[1]['address']) ? "Адрес" : $popular[1]['address'] ?></div></a>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall forSmall margin">
                <a href="<?= empty($popular[0]['id']) ? "/court" : "/court/".$popular[0]['id'] ?>" id="court_link">
                <div style="background-image: url('../img/courts/<?= empty($img[0]['photo']) ? "" : $img[0]['photo'] ?>');" class="images"><div class="players"><i class="fa fa-male" aria-hidden="true"></i><?= empty($popular[0]['count']) ? "" : $popular[0]['count'] ?></div><span>Открыть площадку</span></div>
                <div class="sliderTextPop"><?= empty($popular[0]['address']) ? "Адрес": $popular[0]['address'] ?> </div></a>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall forSmall margin">
                <a href="<?= empty($popular[2]['id']) ? "/court" : "/court/".$popular[2]['id'] ?>" id="court_link">
                <div style="background-image: url('../img/courts/<?= empty($img[2]['photo']) ? "" : $img[2]['photo'] ?>');" class="images"><div class="players"><i class="fa fa-male" aria-hidden="true"></i><?= empty($popular[2]['count']) ? "" : $popular[2]['count'] ?></div><span>Открыть площадку</span></div>
                <div class="sliderTextPop"><?= empty($popular[2]['address']) ? "Адрес" : $popular[2]['address'] ?></div></a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" id="notArena">
    <div class="container">
        <h2 class="h2-white col-lg-12 col-md-12 col-sm-12 col-xs-12">Не нашли площадку?</h2>
        <p class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Хочешь организовать игру на спортивной площадке, а её нет на карте? Не беда! :) Тебе нужно всего лишь отправить нам заявку<br> на её добавление, и в ближайшее время она появится на карте.</p>
        <div class="center col-xs-12"><a class="big-green-btn shadow" href="court/create">Добавить площадку</a></div>
    </div>
</div>