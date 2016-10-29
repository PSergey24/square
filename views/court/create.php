<?php

use yii\helpers\Html;


/* @var $this yii\web\View
/* @var $model app\models\Court
/* @var $map dosamigos\google\maps\Map
*/

$this->title = 'Create Court';
$this->params['breadcrumbs'][] = ['label' => 'Courts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="court-create">

    <h1 style="text-align: center">Добавление площадки</h1>

    <?= $map->display() ?>

    <?= $this->render('_form_create', [
        'model' => $model,
        'district_cities' => $district_cities,
        'types' => $types,
    ]) ?>

</div>

