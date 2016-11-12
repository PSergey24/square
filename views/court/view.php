<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Court */
/* @var $model_form_game_create app\models\forms\GameCreateForm */

use app\assets\AppAsset;
use yii\widgets\Pjax;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Courts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/css/squareProfile.css', [
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

//refresh games block after create new game
$this->registerJs('$("#game-create").on("pjax:end", function() {
           $.pjax.reload({container: "#games"});
       });');
$this->registerJs("
    function collapsElement(id) {
        if (document.getElementsByClassName(id)[0].style.display != \"none\") {
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
            <a id="description_link" href="javascript:void(0)" title="" rel="nofollow" class="link">Описание
                площадки</a><?= Html::img('@web/img/down.png', ['class' => 'arrow', 'id' => '1']) ?>
            <div class="description-text" id="description" style="display: none">
                <p>Классная площадка, с искусстенным газоном. Есть хорошие баскетбольные кольца. Так же есть 2 беговые
                    дорожки. Ворота без сетки, но игре это особо не мешает.</p>
            </div>
        </div>
        <div class="buttons">
            <a class="mid-green-btn shadow" id="bookmark">
                <i class="fa fa-star-o fa-lg" aria-hidden="true"></i>
                <span class="hidden-xs">Добавить в избранные</span>
            </a>

            <button class="mid-blue-btn shadow"><i class="fa fa-heart-o fa-lg" aria-hidden="true"></i><span class="hidden-xs">Мне нравится</span> <span class="players">15</span></button>

        </div>

    </div>
</div>


<div class="container">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
        <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 box chat shadow" id="map">

        </div>
    </div>
    <div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
        <h2 class="h2-box">Ближайшие игры</h2>
        <?php Pjax::begin(['enablePushState' => false, 'id' => 'games']); ?>
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
                    echo 'Сегодня ' . date("H:i", $tm);
                else
                    echo 'Завтра ' . date("H:i", $tm);
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
        <?php Pjax::end(); ?>
        </div>
</div>

<?php if (Yii::$app->user->isGuest): ?>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div class="modal-content game-create create-game">
            <i class="fa fa-times close fa-lg" aria-hidden="true" data-dismiss="modal" ></i>
            <a href="/login"><i class="fa fa-sign-out fa-lg login fa-4x" aria-hidden="true"></a></i>
            <p id="warning">Чтобы выполнить это действие вам нужно <a href="/login">авторизоваться</a>.</p>
        </div>
    </div>
</div>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div class="modal-content game-create create-game">
            <i class="fa fa-times close fa-lg" aria-hidden="true" data-dismiss="modal" ></i>
            <i class="fa fa-check fa-4x ok" aria-hidden="true"></i>
            <p id="warning">Игра успешно создана</p>
        </div>
    </div>
</div>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div class="modal-content game-create create-game">
            <i class="fa fa-times close fa-lg " data-dismiss="modal" aria-hidden="true" id="close"></i>
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
<?php else: ?>
    <?= $this->render('/game/forms/_form_create', ['model' => $model_form_game_create]) ?>
<?php endif; ?>
