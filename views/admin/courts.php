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
?>



<?= $this->render('/admin/_menu') ?>
<h1>Список площадок</h1>
<table class="allCourts">
	<tr>
		<th>Район</th>
		<th>Число плащадок</th>
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
	<td><p class="show" data-district-id="<?= $district['id']; ?>">Показать все площадки</p></td>	
</tr>
<?php 
		$i++;
	}
?>		
</table> 	

<table class="courtDistrict">
	<tr>
		<th>Название площадки</th>
		<th>Число сыгранных игр</th>
		<th>Подписанных участников</th>
	</tr>
	<tr>
		<td colspan="3">Выберите район</td>
	</tr>
</table>