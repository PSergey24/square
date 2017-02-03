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
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDxkhFJ3y--2AadULGUoE9kdlRH3nT-668',
    ['position' => $this::POS_HEAD]
);
$this->registerJsFile(
    '@web/js/markerclusterer.js',['position' => $this::POS_HEAD,'type' => 'text/javascript']
);

$this->registerJs("
    var markerClusterer = null;
    var map = null;
    var myloc_marker, myloc_infoWindow, contentString;
    
    function refreshMap(countMarkers,markerlatlon) {
        var markers = new Array();

        if(markerClusterer){
          markerClusterer.clearMarkers();
        }

        $.each(markerlatlon, function (index, value) {
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            if(value['type_id'] == 1){
                var markerImage = '/img/basket.png';
            }
            else if(value['type_id'] == 2){
                 var markerImage = '/img/foot.png';
            }
            else if(value['type_id'] == 3){
                 var markerImage = '/img/volleball.png';
            }

            if(value['photo'] == 0)
                var photoCourt = 'defaultCourt.png';
            else
                var photoCourt = value['photo'];

          var latLng = new google.maps.LatLng(value['lat'],value['lon']);
          var marker = new google.maps.Marker({
            position: latLng,
            id: value['id'],
            position: latLng,
            map: map,
            animation: google.maps.Animation.DROP,
            type_id: value['type_id'],
            address: value['address'],
            photo: photoCourt,
            bookmark: value['bookmark'],
            visible: true,
            icon: markerImage
          });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.close();
                    if(myloc_infoWindow) { 
                        myloc_infoWindow.close(); 
                    }
                contentString = '<div class=\"searchImgForm court_info\">' +
                                            '<div class=\"forSmall\">' +
                                                '<a href=\"/court/' + this.id + 
                                                '\"><div style=\"background-image: url(/img/courts/' + this.photo + ');\" class=\"image-right image\">' +
                                                '<div class=\"close\"></div><div class=\"players\">' +
                                                    '<i class=\"fa fa-male\" aria-hidden=\"true\"></i>'+ this.bookmark +'</div><span>Открыть площадку</span></div>' +
                                                '<div class=\"sliderText center \">' + this.address + '</div></a>' +
                                            '</div>' +
                                        '</div>';
                infowindow.setContent(contentString);
                infowindow.open(map, marker);
            });
          markers.push(marker);
        })

        var optionsCluster = {
            imagePath: '/img/m'
        };
        markerClusterer = new MarkerClusterer(map, markers, optionsCluster);
    }

    var SPB_lat = 59.910326;
    var SPB_lng = 30.3185942;
    var countMarkers = ".$countMarker.";
    var markerlatlon = ".json_encode($markerAll).";

    function initMap() {
        var latlng = new google.maps.LatLng(SPB_lat, SPB_lng);
        var options = {
            zoom: 11,
            center: latlng,
            mapTypeControl: false,
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoomControl: true,
            scaleControl: false,
            streetViewControl:false
        };
        map = new google.maps.Map(document.getElementById('map'), options);

        refreshMap(countMarkers,markerlatlon);
    }
    google.maps.event.addDomListener(window, 'load', initMap);
", $this::POS_HEAD);


$this->registerJs("
     $('#sport_type').change(function (e) {
        var selectedValue = $('#sport_type').val() != '' ? $('#sport_type').val() : 0;
        $.ajax({
          type: \"POST\",
          url: \"/court/get_points\",
          data: \"typeId=\"+selectedValue,
          success: function(data){
            var markerlatlon = data;
            countMarkers = data.length;
            refreshMap(countMarkers,markerlatlon);
          }
        });
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