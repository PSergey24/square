<?php

use yii\helpers\Html;


/* @var $this yii\web\View
/* @var $model app\models\Court
/* @var $map dosamigos\google\maps\Map
*/

$this->title = 'Create Court';
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

?>
<<<<<<< HEAD
<div class="court-create">

    <h1 style="text-align: center">Добавление площадки</h1>
    
    <?= $this->render('_form_create', [
        'model' => $model,
        'district_cities' => $district_cities,
        'types' => $types,
    ]) ?>
=======
<div class="court-create container">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
        <p class="h2-black">Перетащите метку на место новой площадки</p>
        <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 box shadow" id="map">
           <?= $map->display() ?> 
        </div>
    
>>>>>>> frontend/styles

        
    </div>
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

