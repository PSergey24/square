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
?>



<?= $this->render('/admin/_menu') ?>
<h1>Популярность площадок</h1>

<h2>Самые популярные площадки</h2>
<p class="h2-black">Баскетбол</p>
<div class="court-create container">
		<table class="popularBas col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Название</th>
				<th>Адрес</th>
				<th>Район</th>
				<th>Число игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rowsBas as $Bas) {
		?>	
		<tr>
			<td><a target="_blank" href="/court/<?= $Bas['id']; ?>"><?= $Bas['name']; ?></a></td>	
			<td><?= $Bas['address']; ?></td>
			<td><?= $Bas['nameDistrict']; ?></td>
			<td><?= $Bas['count']; ?></td>	
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>

<p class="h2-black">Футбол</p>
<div class="court-create container">
		<table class="popularBas col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Название</th>
				<th>Адрес</th>
				<th>Район</th>
				<th>Число игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rowsFoot as $Foot) {
		?>	
		<tr>
			<td><a target="_blank" href="/court/<?= $Foot['id']; ?>"><?= $Foot['name']; ?></a></td>	
			<td><?= $Foot['address']; ?></td>
			<td><?= $Foot['nameDistrict']; ?></td>
			<td><?= $Foot['count']; ?></td>	
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>

<p class="h2-black">Волейбол</p>
<div class="court-create container">
		<table class="popularBas col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Название</th>
				<th>Адрес</th>
				<th>Район</th>
				<th>Число игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rowsVball as $Vball) {
		?>	
		<tr>
			<td><a target="_blank" href="/court/<?= $Foot['id']; ?>"><?= $Vball['name']; ?></a></td>	
			<td><?= $Vball['address']; ?></td>
			<td><?= $Vball['nameDistrict']; ?></td>
			<td><?= $Vball['count']; ?></td>	
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>

<h2>Наименее популярные площадки</h2>
<p class="h2-black">Баскетбол</p>
<div class="court-create container">
		<table class="popularBas col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Название</th>
				<th>Адрес</th>
				<th>Район</th>
				<th>Число игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rowsBasLeast as $BasLeast) {
		?>	
		<tr>
			<td><a target="_blank" href="/court/<?= $Bas['id']; ?>"><?= $BasLeast['name']; ?></a></td>	
			<td><?= $BasLeast['address']; ?></td>
			<td><?= $BasLeast['nameDistrict']; ?></td>
			<td><?= $BasLeast['count']; ?></td>	
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>

<p class="h2-black">Футбол</p>
<div class="court-create container">
		<table class="popularBas col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Название</th>
				<th>Адрес</th>
				<th>Район</th>
				<th>Число игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rowsFootLeast as $FootLeast) {
		?>	
		<tr>
			<td><a target="_blank" href="/court/<?= $Foot['id']; ?>"><?= $FootLeast['name']; ?></a></td>	
			<td><?= $FootLeast['address']; ?></td>
			<td><?= $FootLeast['nameDistrict']; ?></td>
			<td><?= $FootLeast['count']; ?></td>	
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>

<p class="h2-black">Волейбол</p>
<div class="court-create container">
		<table class="popularBas col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<tr>
				<th>Название</th>
				<th>Адрес</th>
				<th>Район</th>
				<th>Число игр</th>
			</tr>
		<?php 
		// var_dump($districts);
			$i = 0;
			foreach ($rowsVballLeast as $VballLeast) {
		?>	
		<tr>
			<td><a target="_blank" href="/court/<?= $Foot['id']; ?>"><?= $VballLeast['name']; ?></a></td>	
			<td><?= $VballLeast['address']; ?></td>
			<td><?= $VballLeast['nameDistrict']; ?></td>
			<td><?= $VballLeast['count']; ?></td>	
		</tr>
		<?php 
				$i++;
			}
		?>		
		</table> 	
</div>
