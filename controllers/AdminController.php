<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use app\models\Court;
use app\models\SportType;
use app\models\GameUser;
use app\models\CourtPhoto;
use app\models\DistrictCity;
use yii\web\Response;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\forms\GameCreateForm;
use dektrium\user\controllers\AdminController;
use DateTime;
use DateInterval;

/**
 * GameController implements the CRUD actions for Game model.
 */
class AdminController extends AdminController
{
    public function actionStat()
    {
    	$a = 778;
        return $this->render('stat',[
            'a' => $a,
        ]);
    }
    
    public function actionPhoto()
    {
    	$query = new Query;
        $query->select('*')
              ->from('court_photo')
              ->where('flag_moderation = 1');
        $rows = $query->all();

        return $this->render('photo',[
            'photo' => $rows,
        ]);
    }

    public function actionAdd_photo()
    {
    	$id = Yii::$app->getRequest()->getBodyParam("id");
    	$tr = Yii::$app->getRequest()->getBodyParam("tr");

    	$courtPhoto = Yii::createObject(CourtPhoto::className());
        $photo = $courtPhoto->find()->where(['id' => $id])->one();

        $photo->flag_moderation = 0;
        
        if($photo->save())
        	return $tr;
        else
        	return 'ошибка';
    }

    public function actionDelete_photo()
    {
    	$id = Yii::$app->getRequest()->getBodyParam("id");
    	$tr = Yii::$app->getRequest()->getBodyParam("tr");

    	$courtPhoto = Yii::createObject(CourtPhoto::className());
        $photo = $courtPhoto->findOne(['id' => $id]);
        
        // echo $photo->delete();
        if($photo->delete())
        	return $tr;
        else
        	return 'ошибка';
    }

    public function actionCourts()
    {
    	// Yii::$app->response->format = Response::FORMAT_JSON;
    	$districts = DistrictCity::find()->all();

    	$count = array();
    	$countB = array();
    	$countF = array();
    	$i = 0;
    	foreach ($districts as $district) {
    		$query = new Query;
	        $query->select('count(district_city_id) as count')
	              ->from('court')
	              ->where(['district_city_id' => $district['id']]);
	        $rows = $query->one();

	        $query = new Query;
	        $query->select('count(district_city_id) as count')
	              ->from('court')
	              ->where(['district_city_id' => $district['id']])
	              ->andWhere('type_id = 1');
	        $rowsB = $query->one();
	        $query->select('count(district_city_id) as count')
	              ->from('court')
	              ->where(['district_city_id' => $district['id']])
	              ->andWhere('type_id = 2');
	        $rowsF = $query->one();
	        $count[$i] = $rows['count'];
	        $countB[$i] = $rowsB['count'];
	        $countF[$i] = $rowsF['count'];
    		// echo $district['id']." | ".$district['name']." | ".$rows['count']."</br>";
    		$i++;
    	}

        return $this->render('courts',[
            'districts' => $districts,
            'count' => $count,
            'countB' => $countB,
            'countF' => $countF,
        ]);
    }

    public function actionShow()
    {
    	$id = Yii::$app->getRequest()->getBodyParam("id");
    	// $id = 5;
    		$query = new Query;
	        $query->select('name')
	              ->from('district_city')
	              ->where(['id' => $id]);
	        $distr = $query->one();

	        $query->select('name, id')
	              ->from('court')
	              ->where(['district_city_id' => $id]);
	        $rows = $query->all();

	        
	        // var_dump($countBookmark);

	    $string = '<tr>
						<th>Название площадки</th>
						<th>Число сыгранных игр</th>
						<th>Подписанных участников</th>
					</tr>
					<tr>
						<td colspan="3">'.$distr['name'].' район</td>
					</tr>';
		$i = 0;
	    foreach ($rows as $item) {
	    	$query->select('count(user_id) as count')
	              ->from('court_bookmark')
	              ->where(['court_id' => $item['id']]);
	        $countBookmark = $query->all();

	        $query->select('count(id) as count')
	              ->from('game')
	              ->where(['court_id' => $item['id']])
	              ->andWhere(['<','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))]);
	        $countGame = $query->all();

	        // var_dump($countBookmark);

	    	$string = $string.'<tr>
									<td><a target="_blank" href="/court/'.$item['id'].'">'.$item['name'].'</a></td>
									<td>'.$countGame[0]['count'].'</td>
									<td>'.$countBookmark[0]['count'].'</td>
								</tr>';
			$i++;
	    }
    	// echo $string;
        return $string;
    }
}