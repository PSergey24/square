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
<h1>Жалобы на площадки</h1>
<?php 
	if(count($report) > 0)
	{
?>		
<div class="court-create container">
 	<table class="tableReport col-lg-12 col-md-12 col-sm-12 col-xs-12">
 		<tr>
 			<th>Площадка</th>
 			<th>Тема</th>
 			<th>Суть жалобы</th>
 			<th>Кто подал жалобу?</th>
 		</tr>
		<?php 
			foreach ($report as $item) {
		 ?>		

 		<tr>
 			<td><?= $item['court_id']; ?></td>
 			<td><?= $item['title']; ?></td>
 			<td><?= $item['description']; ?></td>
 			<td><?= $item['user_id']; ?></td>
 		</tr>

		<?php 		
			}
		 ?>
	</table>
</div>
<?php 
}else{
	echo "Нет жалоб на площадки";
}
?>	