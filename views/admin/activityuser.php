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
$this->registerMetaTag([
'name' => 'description',
'content' => 'ваш текст'
]);

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
	$chartData = $chartData.'{district: "'.$dis['username'].'",count: '.$dis['count'].'},';
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
<h1>Самые активные пользователи</h1>

<div class="court-create container">
		<div id="chartdiv" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height: 400px;"></div>
		<table class="activity col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Пользователь</th>
				<th>Число проведенных игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rows as $user) {
		?>	
		<tr>
			<td><a target="_blank" href="/users/<?= $user['id']; ?>"><?= $user['username']; ?></a></td>	
			<td><?= $user['count']; ?></td>
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>
