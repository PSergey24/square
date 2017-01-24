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
$this->registerCssFile('/css/export.css');
$this->registerJsFile(
    '@web/js/admin.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/js/amcharts.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/js/pie.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$chartData = '';
$i=0;
foreach ($districts as $dis) {
	$chartData = $chartData.'{district: "'.$dis['name'].'",court: '.$count[$i].'},';
	$i++;
}
$this->registerJs("

            var chart = AmCharts.makeChart( \"chartdiv\", {
				\"type\": \"pie\",
				\"dataProvider\": [".$chartData."],
				\"titleField\": \"district\",
				\"valueField\": \"court\",
				\"balloonText\": \"[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>\",
				\"innerRadius\": \"30%\",
				\"export\": {
					\"enabled\": true
				}
			} );
");

$this->registerJs("

            var chart = AmCharts.makeChart( \"chartsport\", {
				\"type\": \"pie\",
				\"dataProvider\": [ {
					\"sport\": \"Баскетбол\",
					\"num\": ".$totalBasketball."
				}, {
					\"sport\": \"Футбол\",
					\"num\": ".$totalFootball."
				}],
				\"titleField\": \"sport\",
				\"valueField\": \"num\",
				\"balloonText\": \"[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>\",
				\"innerRadius\": \"30%\",
				\"export\": {
					\"enabled\": true
				}
			} );
");


$this->registerJsFile(
    '@web/js/plugins/export/export.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
?>



<?= $this->render('/admin/_menu') ?>
<h1>Список площадок</h1>
<div class="court-create container">
		<p class="h2-black">Все площадки</p>
		<div id="chartdiv" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height: 400px;"></div>
		<p class="h2-black">Виды спорта</p>
		<div id="chartsport" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height: 400px;"></div>
		<table class="allCourts col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Район</th>
				<th>Число</br> плащадок</th>
				<th>Баскетбольных</br> плащадок</th>
				<th>Футбольных</br> плащадок</th>
				<th></th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($districts as $district) {
		?>	
		<tr>
			<td><?= $district['name']; ?></td>	
			<td><?= $count[$i]; ?></td>
			<td><?= $countB[$i]; ?></td>	
			<td><?= $countF[$i]; ?></td>		
			<td><button class="show" data-district-id="<?= $district['id']; ?>">Показать все площадки</button></td>	
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>


<div class="court-create container">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<table class="courtDistrict">
			<tr>
				<th>Название</br> площадки</th>
				<th>Спорт</th>
				<th>Число сыгранных</br> игр</th>
				<th>Подписанных</br> участников</th>
			</tr>
			<tr>
				<td colspan="4">Выберите район</td>
			</tr>
		</table>

	</div>
</div>