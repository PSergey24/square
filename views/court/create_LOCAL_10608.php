<?php

use yii\helpers\Html;


/* @var $this yii\web\View
/* @var $model app\models\Court
/* @var $map dosamigos\google\maps\Map
*/

$this->title = 'Create Court';
$this->params['breadcrumbs'][] = ['label' => 'Courts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

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
<div class="court-create">

    <h1 style="text-align: center">Добавление площадки</h1>
    
    <?= $this->render('_form_create', [
        'model' => $model,
        'district_cities' => $district_cities,
        'types' => $types,
    ]) ?>

</div>

