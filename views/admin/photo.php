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
<h1>Модерация картинок</h1>
<?php 
	if(count($photo) > 0)
	{
?>		
 	<table class="tablePhoto">
 		<tr>
 			<th>Фото</th>
 			<th>Площадка</th>
 			<th></th>
 			<th></th>
 		</tr>
		<?php 
			$i = 0;
			foreach ($photo as $image) {
			$i++;
		 ?>		

 		<tr data-tr="<?= $i ?>">
 			<td><img src="/img/courts/<?php echo $image['photo']; ?>"></td>
 			<td><?php echo $image['court_id']; ?></td>
 			<td><p class="add" data-id-photo="<?php echo $image['id']; ?>" data-num-tr="<?= $i ?>">Добавить</p></td>
 			<td><p class="delete" data-id-photo="<?php echo $image['id']; ?>" data-num-tr="<?= $i ?>">Удалить</p></td>
 		</tr>

		<?php 		
			}
		 ?>
	</table>
<?php 
}else{
	echo "Нет новых фотографий";
}
?>	