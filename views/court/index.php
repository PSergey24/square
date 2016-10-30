<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CourtSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Courts';
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="/css/searchArena.css">
<script>
    var map;
    var markers = new Array();
    function initMap() {
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
    }
    // sport_type select
    $(document).ready(function () {
        var court_images = [
            'court_img_0.jpg',
            'court_img_1.jpg',
            'court_img_2.jpg',
            'court_img_3.jpg',
            'court_img_4.jpg',
            'court_img_5.jpg',
            'court_img_6.jpg',
            'court_img_7.jpg',
            'court_img_8.jpg',
            'court_img_9.jpg',
            'court_img_10.jpg',
            'court_img_11.jpg',
            'court_img_12.jpg',
            'court_img_13.jpg',
            'court_img_14.jpg',
            'court_img_15.jpg',
            'court_img_16.jpg',
            'court_img_17.jpg',
            'court_img_18.jpg',
            'court_img_19.jpg',
            'court_img_20.jpg',
            'court_img_21.jpg',
        ];

        $.ajax({url: 'court/get_points', success: function(result) {
            var sport_type = getUrlVars()['sport_type'];

            if (!sport_type)
                sport_type = 0;

            var visible_val;
            $('#sport_type').val(sport_type);
            $.each(result, function (index, value) {
                var rnd = Math.floor(Math.random() * (20 - 0 + 1)) + 0;
                if(value['type_id'] == sport_type || sport_type == 0)
                    visible_val = true;
                else
                    visible_val = false;
                if (value['type_id'] == 1) {
                    var pinImgLink = 'img/basket.png';
                }else
                    var pinImgLink = 'img/foot.png';

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
                    $('#court_link').attr('href', 'court/view/' + this.id);
                    $('#address').text(this.address);
                    $('#court_photo').css('background-image', 'url(img/' + this.photo +')');
                    $('#court_info').css('display', 'block');
                });
                markers.push(marker);
            })
        }});
    });
    $(document).ready(function() {
        $('#sport_type').change(function (e) {
            for (var i = 0; i < markers.length; i++) {
                if (markers[i].type_id !== $('#sport_type').val()) {
                    console.log(markers[i].type_id);
                    markers[i].setVisible(false);
                }else {
                    markers[i].setVisible(true);
                }
            }
        })
    });

</script>

<div class="container-fluid" id="center" >
    <div class="container">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">
            <div class="searchImgForm col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">
                    <form class="box shadow">
                        <input disabled type="text" placeholder="Санкт-Петербург">
                        <select id="sport_type">
                            <option value="" disabled selected style="display: none;">Вид спорта</option>
                            <option value="1">Баскетбол</option>
                            <option value="2">Футбол</option>
                        </select>
                        <input type="button" class="mid-green-btn" value="Поиск">
                    </form>
                </div>
            </div>

            <div class="box shadow searchImgBox col-lg-7 col-md-7 col-sm-7 col-xs-12">
                <div id="map"></div>
                <div class="Arena">
                    <span class="chooseArena hidden-xs">Выберите площадку на карте</span>
                    <span class="addArena">+Добавить площадку</span>
                    <span class="nearArena">Близжайшие к вам</span>
                </div>
            </div>
            <div id="court_info" class="searchImgForm col-lg-5 col-md-5 col-sm-5 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">
                    <div style="background-image: url(img/arena.jpg);" class="image-right" id="court_photo">
                        <a href="#" id="court_link"><div class="open"><span>Открыть</span></div></a>
                    </div>
                    <div class="underImg box shadow" id="address">Измайловский пр. д.86</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" id="slider">
    <div class="container containerSlider">
        <h1 class="h1-black col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">Популярные площадки</h1>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 forSmall">
            <div style="background-image: url(../img/court_img_22.jpg);" class="image"><a href="<?= '/court/view/' . $popular[0]['id'] ?>" id="court_link"><div class="open"><span>Открыть</span></div></a></div>
            <div class="sliderText box shadow"><?= $popular[0]['address'] ?></div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 forSmall">
            <div style="background-image: url(../img/court_img_23.jpg);" class="image"><a href="<?= '/court/view/' . $popular[2]['id'] ?>" id="court_link"><div class="open"><span>Открыть</span></div></a></div>
            <div class="sliderText box shadow"><?= $popular[2]['address'] ?></div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 forSmall">
            <div style="background-image: url(../img/court_img_24.jpg);" class="image"><a href="<?= '/court/view/' . $popular[1]['id'] ?>" id="court_link"><div class="open"><span>Открыть</span></div></a></div>
            <div class="sliderText box shadow"><?= $popular[1]['address'] ?></div>
        </div>
    </div>
</div>

<div class="container-fluid" id="notArena">
    <div class="container">
        <h2 class="h1-white col-lg-12 col-md-12 col-sm-12 col-xs-12">Не нашли площадку?</h2>
        <p class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Хочешь организовать игру на спортивной площадке, а её нет на карте? Не беда!:) Тебе нужно всего лишь отправить нам заявку на её добавление и в ближайшее время она появится на карте.</p>
        <p><input class="big-green-btn shadow" type="button" value="Добавить площадку"></p>
    </div>
</div>
<script>
    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    }
</script>