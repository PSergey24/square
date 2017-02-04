<?php

use yii\helpers\Html;


/* @var $this yii\web\View
/* @var $model app\models\Court
/* @var $map dosamigos\google\maps\Map
*/

$this->title = 'Добавление новой площадки';
$this->params['breadcrumbs'][] = ['label' => 'Courts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/css/create.css');
$this->registerJs("
    //set name to description in marker
    $('#court-name').on('keyup', function() {
    
        var court_name = $('#court-name').val();
        var court_description = $('#court_description');
    
        if (court_name != '')
            court_description.text(court_name);
        else
            court_description.text($('#court-name').attr('placeholder'))
    });
");

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDxkhFJ3y--2AadULGUoE9kdlRH3nT-668&callback=initMap',
    [
        'async' => true,
    ]
);

$this->registerJs("
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
        var single_marker = new google.maps.Marker({
            position: latlng,
            map: map,
            animation: google.maps.Animation.DROP,
            draggable:true,
            title:\"Установите маркер на месте площадки\"
        });
        var infoWindowContent = '<p id=\"court_description\">Введите название площадки</p>';
        var infowindow = new google.maps.InfoWindow({
          content: infoWindowContent
        });
        single_marker.addListener('click', function() {
          infowindow.open(map, single_marker);
        });
        google.maps.event.addListener(single_marker, 'dragend', function(evt) { 
            var lat = single_marker.getPosition().lat();
            var lng = single_marker.getPosition().lng();
            //set hidden input's value
            $('#court-lat').val(lat);
            $('#court-lon').val(lng);
            //decode marker position coordinates to address string
            var geocoder = new google.maps.Geocoder;
            var latlng = {lat: lat, lng: lng};
            geocoder.geocode({'location': latlng}, function(results, status) {
            if (status === 'OK') {
                //set address string to input
                $('#court-address').val(results[0].formatted_address);
            //                        console.log(results);
            }
            });
            
            if ($('#court-name').val() != '')
            $('#court_description').text($('#court-name').val());            
        });
    }
", $this::POS_HEAD);
$this->registerJs("
    $('#court-name').on('keyup', function() {

    var court_name = $('#court-name').val();
    var court_description = $('#court_description');

    if (court_name != '')
        court_description.text(court_name);
    else
        court_description.text($('#court-name').attr('placeholder'))
});
");
?>

<div class="court-create container">
    <p class="h2-black">Перетащите метку на место новой площадки</p>
    <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 box shadow" id="map"></div>
    <div class="col-lg-8 col-lg-offset-2 col-md-12 col-sm-12 col-xs-12 features">
        <p class="h2-black">Описание площадки</p>
        <div class="col-lg-12 col-xs-12 box options shadow">
            <?= $this->render('_form_create', [
                    'model' => $model,
                    'district_cities' => $district_cities,
                    'types' => $types,
            ]) ?>
        </div>
    </div>
</div>


