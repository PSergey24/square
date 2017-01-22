<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */

$this->registerCssFile('/css/admin.css');

$this->registerJsFile(
    '@web/js/admin.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDxkhFJ3y--2AadULGUoE9kdlRH3nT-668&callback=initMap',
    [
        'async' => true,
    ]

);

$this->registerJs("
    var directionsDisplay, directionsService, map;
    function initMap(lat,lon) {
        var directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer();
        var latlng = new google.maps.LatLng(lat, lon);
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
            $('#input-lat').attr('value',lat);
            $('#input-lon').attr('value',lng);
            //decode marker position coordinates to address string
            var geocoder = new google.maps.Geocoder;
            var latlng = {lat: lat, lng: lng};
            geocoder.geocode({'location': latlng}, function(results, status) {
            if (status === 'OK') {
                //set address string to input
                $('#input-address').val(results[0].formatted_address);
            //                        console.log(results);
            }
            });
            
            if ($('#court-name').val() != '')
            $('#court_description').text($('#court-name').val());            
        });
    }

", $this::POS_HEAD);

?>



<?= $this->render('/admin/_menu') ?>


<h1>Модерация площадок</h1>

<div class="court-create container">
    <p class="h2-black">Выберите площадку, отредактируйте её.</p>
    <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 box shadow" id="map"></div>
    <div class="col-lg-8 col-lg-offset-2 col-md-12 col-sm-12 col-xs-12"></br>
        <p class="h2-black">Описание площадки</p></br>
        <div class="col-lg-12 col-xs-12 box options shadow">
 			<div class="group-field">
 				<label for="input-address" class="col-lg-3">Адрес</label><input class="col-lg-7 col-lg-offset-2" id="input-address" type="text" value="">
 			</div>
 			<div class="group-field">
 				<label for="input-name" class="col-lg-3">Имя</label><input class="col-lg-7 col-lg-offset-2" id="input-name" type="text" value="">
 			</div>
 			<div class="group-field">
 				<label for="input-area" class="col-lg-3">Площадь</label><input class="col-lg-7 col-lg-offset-2" id="input-area" type="text" value="">
 			</div>
 			<div class="group-field">
 				<label for="input-district" class="col-lg-3">Район</label><input class="col-lg-7 col-lg-offset-2" id="input-district" type="text" value="">
 			</div>
 			<div class="group-field">
 				<label for="input-type" class="col-lg-3">Вид спорта</label><input class="col-lg-7 col-lg-offset-2" id="input-type" type="text" value="">
 			</div>
 			<input hidden class="col-lg-7 col-lg-offset-2" id="input-id" type="text" value="">
 			<input hidden class="col-lg-7 col-lg-offset-2" id="input-lat" type="text" value="">
 			<input hidden class="col-lg-7 col-lg-offset-2" id="input-lon" type="text" value="">
 			<button id="addCourt">Добавить</button>
 			<button id="deleteCourt">Удалить</button>
        </div>



    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		    
<?php 
	if(count($courts) > 0)
	{
?>		
 	<table class="tableNewCourt">
 		<tr>
 			<th>id</th>
 			<th>Адрес</th>
 			<th>Имя</th>
 			<th>Площадь</th>
 			<th>Район</th>
 			<th>Тип</th>
 			<th></th>
 		</tr>
		<?php 
			$i = 0;
			foreach ($courts as $item) {
			$i++;
		 ?>		

 		<tr data-tr="<?= $i ?>" data-lat="<?php echo $item['lat']; ?>" data-lon="<?php echo $item['lon']; ?>">
 			<td class="item-id"><?php echo $item['id']; ?></td>
 			<td class="item-address"><?php echo $item['address']; ?></td>
 			<td class="item-name"><?php echo $item['name']; ?></td>
 			<td class="item-area"><?php echo $item['built_up_area']; ?></td>
 			<td class="item-district"><?php echo $item['district_city_id']; ?></td>
 			<td class="item-type"><?php echo $item['type_id']; ?></td>
 			<td><p class="edit" data-tr-num="<?= $i ?>" onclick="initMap(<?php echo $item['lat']; ?>,<?php echo $item['lon']; ?>)">Редактировать</p></td>
 		</tr>
 		</tr>

		<?php 		
			}
		 ?>
	</table>
<?php 
}else{
	echo "Нет новых площадок";
}
?>	

    </div>
</div>
