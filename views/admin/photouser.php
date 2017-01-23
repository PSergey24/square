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
<h1>Модерация фотографий пользователей</h1>


<div class="court-create container">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		    
<?php 
	if(count($users) > 0)
	{
?>		
 	<table class="tableAvatar">
 		<tr>
 			<th>Пользователь</th>
 			<th>Аватар</th>
 			<th></th>
 		</tr>
		<?php 
			$i = 0;
			foreach ($users as $item) {
			
		 ?>		

 		<tr data-tr="<?= $i ?>" data-id-user="<?php echo $item['id']; ?>">
 			<td class="item-username"><?php echo $item['username']; ?></td>
 			<td class="item-picture"><img data-picture="<?php echo $item['picture']; ?>" src="/img/uploads/<?php echo $item['picture']; ?>" ></td>
 			<td><button type="text" class="deleteAvatar" data-tr-num="<?= $i ?>">Удалить</button></td>
 		</tr>
 		</tr>

		<?php 	
			$i++;	
			}
		 ?>
	</table>
<?php 
}else{
	echo "Нет фотографий";
}
?>	

    </div>
</div>
