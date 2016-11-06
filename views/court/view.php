<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Court */

cakebake\bootstrap\select\BootstrapSelectAsset::register($this);

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\SportType;
use app\assets\AppAsset;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Courts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/css/squareProfile.css',[
    'depends' => [AppAsset::className()]
]);

$this->registerJs("
    var map;
    var court = " . $court_json . ";
    function initMap() {
        var latlng = new google.maps.LatLng(court.lat, court.lon);
        var options = {
            zoom: 15,
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

        if (court.type_id == 1) {
            var pinImgLink = '/img/basket.png';
        }else
            var pinImgLink = '/img/foot.png';

        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            animation: google.maps.Animation.DROP,
            icon: pinImgLink
        });
    }
", $this::POS_HEAD);
$this->registerJs("
    // On submit btn 'Create game' add game block 
    $('#game-create').submit(function () {
        var game_html = $('#game_list').html();
        if ($('#game-need_ball').prop('checked'))
           var ballImg = '<img src=\"/img/ball-ok.png\">';
        else
           var ballImg = '<img src=\"/img/ball-not.png\">';
        
        var game = '<div class=\"game\">' +
                      '<div class=\"time\">' + $('#game-time :selected').text() + ' ' + $('#time').val() +
        
                      '</div>' + ballImg+
                      '<button class=\"mid-blue-btn\">+ ' +
                      '<span class=\"players\">1  </span><' +
                      '/button><' +
                '/div>';
        
        game_html = game + game_html;
        $('#game_list').html(game_html);
    });
    
    //Bookmark btn onclick change pic and text
    $('#bookmark').click(function () {
            var url = document.URL;
            var id = url.substring(url.lastIndexOf('/') + 1);
            $.ajax({
                url: '/court/bookmark',
                data: {court_id: id},
                success: function(success) {
                    $('#bookmark img').attr('src', '/img/star-active.png');
                    $('#bookmark span').text('Удалить из избранного');
                    console.log(success);
                },
            });
        });
    //Description link on click smoothly fade in description block
    $('#description_link').click(function () {
        $('#description').toggle(300);
    });
");
?>

<div class="container-fluid top">
    <div class="container s">

        <h2 class="h2-white"><?= $court['address'] ?></h2>
        <p>
            <?php
            if ($court['type_id'] != 1)
                echo Html::a('Футбол', Url::to(['/court', 'sport_type' => 2], true), [
                        'class' => 'tag'
                      ]);
            else
                echo Html::a('Баскетбол', Url::to(['/court', 'sport_type' => 1], true), [
                    'class' => 'tag'
                ]);;
            ?>
        </p>
        <div class="description ">
            <a id="description_link" href="javascript:void(0)" title="" rel="nofollow" class="link">Описание площадки</a><?= Html::img('@web/img/down.png', ['class' => 'arrow', 'id' => '1']) ?>
            <div class="description-text" id="description" style="display: none">
                <p>Классная площадка, с искусстенным газоном. Есть хорошие баскетбольные кольца. Так же есть 2 беговые дорожки. Ворота без сетки, но игре это особо не мешает.</p>
            </div>
        </div>
        <div class="buttons">
            <a class="mid-green-btn shadow" id="bookmark">
                <?= Html::img('@web/img/star.png', ['class' => 'img']) ?>
                <span class="hidden-xs">Добавить в избранные</span>
            </a>
            <button class="mid-blue-btn shadow"><?= Html::img('@web/img/heart.png', ['class' => 'img']) ?><span class="hidden-xs">Мне нравится</span> <span class="players">15</span></button>
        </div>

    </div>
</div>



<div class="container">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
        <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 box chat" id="map">

        </div>
    </div>
    <div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
        <h2 class="h2-box">Ближайшие игры</h2>
        <div class="col-lg-12 col-xs-12 box games" id="game_list">
                     <?php
                        foreach ($games as $game) {
                            echo '<div class="game">
                <div class="time">';
                            $tm = strtotime($game['time']);
                            $current_datetime = new DateTime();
                            $current_datetime = date_format($current_datetime, 'Y-m-d');
                            $tm_current = strtotime($current_datetime);
                            if (date("d", $tm) == date("d", $tm_current))
                                echo 'Сегодня ' . date("H:m", $tm);
                            else
                                echo 'Завтра ' . date("H:m", $tm);
                            echo '</div>';
                            if ($game['need_ball'] == 1)
                                echo Html::img('@web/img/ball-ok.png');
                            else
                                echo Html::img('@web/img/ball-not.png');
                            echo '<button class="mid-blue-btn">+ <span class="players">1</span></button></div>';
                        }
                     ?>
            <button class="mid-green-btn" data-toggle="modal" data-target=".bs-example-modal-lg">Создать игру</button>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div class="modal-content game-create" class="create-game ">
            <p class="h2-black">Создание игры</p>
        <?php
            $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'id' => 'game-create',
                'enableAjaxValidation' => true,
            ]);
        ?>
           <?= Html::hiddenInput('court_id', Yii::$app->getRequest()->getQueryParam('id'));
           ?>
            <p class="little">Выберите время игры</p>
                <p class="align-right">
                    <?php
                        $select_day = [
                            '0' => 'Сегодня',
                            '1' => 'Завтра'
                        ];
                        echo Html::activeDropDownList($model_game, 'time', $select_day, [
                            'class' => 'selectpicker input date',
                        ]);

                    ?>
                    <?= Html::input('time', 'time_digit', null, [
                        'class' => 'input date',
                        'id' => 'time'
                        ])
                    ?>
                    </p>
            <p class="ball">
                <?= $form->field($model_game, 'need_ball')->checkbox(['label' => 'Нужен мяч']); ?>

            <?= Html::submitButton('Готово', ['class' => 'mid-green-btn']) ?>

            <?php ActiveForm::end() ?>

        </div>
    </div>
</div>

<script>
    function collapsElement(id) {
        if ( document.getElementsByClassName(id)[0].style.display != "none" ) {
            document.getElementsByClassName(id)[0].style.display = 'none';
            document.getElementsByClassName('link')[0].style.color = 'rgba(255,255,255,0.7)';
            document.getElementById('1').src = '/img/down.png';
            document.getElementById('1').style.opacity = '0.7';
        }
        else {
            document.getElementsByClassName(id)[0].style.display = '';
            document.getElementsByClassName('link')[0].style.color = 'rgba(255,255,255,1)';
            document.getElementById('1').src = '/img/up.png';
            document.getElementById('1').style.opacity = '1';
        }
    }
</script>
