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
    '@web/js/amcharts.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/js/pie.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$chartData = '';
foreach ($rows as $dis) {
	$chartData = $chartData.'{district: "'.$dis['name'].'",count: '.$dis['count'].'},';
}
$chartDataBas = '';
foreach ($rowsBas as $disBas) {
	$chartDataBas = $chartDataBas.'{district: "'.$disBas['name'].'",count: '.$disBas['count'].'},';
}
$chartDataFoot = '';
foreach ($rowsFoot as $disFoot) {
	$chartDataFoot = $chartDataFoot.'{district: "'.$disFoot['name'].'",count: '.$disFoot['count'].'},';
}
$chartDataVball = '';
foreach ($rowsVball as $disVball) {
	$chartDataVball = $chartDataVball.'{district: "'.$disVball['name'].'",count: '.$disVball['count'].'},';
}
$this->registerJs("

            var chart = AmCharts.makeChart( \"chartdiv\", {
				\"type\": \"pie\",
				\"theme\": \"light\",
				\"dataProvider\": [".$chartData."],
				\"titleField\": \"district\",
				\"valueField\": \"count\",
				\"balloonText\": \"[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>\",
				\"innerRadius\": \"30%\",
				\"export\": {
					\"enabled\": true
				}
			} );
");

$this->registerJs("

            var chart = AmCharts.makeChart( \"chartBas\", {
				\"type\": \"pie\",
				\"theme\": \"light\",
				\"dataProvider\": [".$chartDataBas."],
				\"titleField\": \"district\",
				\"valueField\": \"count\",
				\"balloonText\": \"[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>\",
				\"innerRadius\": \"30%\",
				\"export\": {
					\"enabled\": true
				}
			} );
");

$this->registerJs("

            var chart = AmCharts.makeChart( \"chartFoot\", {
				\"type\": \"pie\",
				\"theme\": \"light\",
				\"dataProvider\": [".$chartDataFoot."],
				\"titleField\": \"district\",
				\"valueField\": \"count\",
				\"balloonText\": \"[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>\",
				\"innerRadius\": \"30%\",
				\"export\": {
					\"enabled\": true
				}
			} );
");

$this->registerJs("

            var chart = AmCharts.makeChart( \"chartVball\", {
				\"type\": \"pie\",
				\"theme\": \"light\",
				\"dataProvider\": [".$chartDataVball."],
				\"titleField\": \"district\",
				\"valueField\": \"count\",
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
$this->registerJsFile(
    'https://www.amcharts.com/lib/3/themes/light.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

?>



<?= $this->render('/admin/_menu') ?>
<h1>Активность районов</h1>

<div class="court-create container">
		<p class="h2-black">Все площадки</p>
		<div id="chartdiv" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height: 400px;"></div>
		<table class="activity col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Район</th>
				<th>Число проведенных игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rows as $district) {
		?>	
		<tr>
			<td><?= $district['name']; ?></td>	
			<td><?= $district['count']; ?></td>
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>

<div class="court-create container">
		</br><p class="h2-black">Только баскетбольные площадки</p>
		<div id="chartBas" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height: 400px;"></div>
		<table class="activity col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Район</th>
				<th>Число проведенных игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rowsBas as $districtBas) {
		?>	
		<tr>
			<td><?= $districtBas['name']; ?></td>	
			<td><?= $districtBas['count']; ?></td>
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>

<div class="court-create container">
		</br><p class="h2-black">Только футбольные площадки</p>
		<div id="chartFoot" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height: 400px;"></div>
		<table class="activity col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Район</th>
				<th>Число проведенных игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rowsFoot as $districtFoot) {
		?>	
		<tr>
			<td><?= $districtFoot['name']; ?></td>	
			<td><?= $districtFoot['count']; ?></td>
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>

<div class="court-create container">
		</br><p class="h2-black">Только волейбольные площадки</p>
		<div id="chartVball" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height: 400px;"></div>
		<table class="activity col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Район</th>
				<th>Число проведенных игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rowsVball as $districtVball) {
		?>	
		<tr>
			<td><?= $districtVball['name']; ?></td>	
			<td><?= $districtVball['count']; ?></td>
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>