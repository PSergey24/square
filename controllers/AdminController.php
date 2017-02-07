<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use app\models\Profile2;
use app\models\Court;
use app\models\Report;
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
use dektrium\user\controllers\AdminController as BaseAdminController;
use DateTime;
use DateInterval;

/**
 * GameController implements the CRUD actions for Game model.
 */
class AdminController extends BaseAdminController
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
              ->where('approved = 1');
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
        $photo = $courtPhoto->findOne(['id' => $id]);

        $photo->approved = 0;
        
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
        {
            unlink('img/courts/'.$photo['photo']);
        	return $tr;
        }
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
      $countV = array();
    	$i = 0;
        $totalBasketball = 0;
        $totalFootball = 0;
        $totalVolleyball = 0;
    	foreach ($districts as $district) {
    		$query = new Query;
	        $query->select('count(district_city_id) as count')
	              ->from('court')
	              ->where(['district_city_id' => $district['id']])
                  ->andWhere('approved = 0');
	        $rows = $query->one();

	        $query = new Query;
	        $query->select('count(district_city_id) as count')
	              ->from('court')
	              ->where(['district_city_id' => $district['id']])
	              ->andWhere('type_id = 1')
                  ->andWhere('approved = 0');;
	        $rowsB = $query->one();
	        $query->select('count(district_city_id) as count')
	              ->from('court')
	              ->where(['district_city_id' => $district['id']])
	              ->andWhere('type_id = 2')
                  ->andWhere('approved = 0');
	        $rowsF = $query->one();
          $query->select('count(district_city_id) as count')
                ->from('court')
                ->where(['district_city_id' => $district['id']])
                ->andWhere('type_id = 3')
                  ->andWhere('approved = 0');
          $rowsV = $query->one();
	        $count[$i] = $rows['count'];
	        $countB[$i] = $rowsB['count'];
	        $countF[$i] = $rowsF['count'];
          $countV[$i] = $rowsV['count'];
            $totalFootball = $totalFootball + $countF[$i];
            $totalBasketball = $totalBasketball + $countB[$i];
            $totalVolleyball = $totalVolleyball + $countV[$i];
    		// echo $district['id']." | ".$district['name']." | ".$rows['count']."</br>";
    		$i++;
    	}

        return $this->render('courts',[
            'districts' => $districts,
            'count' => $count,
            'countB' => $countB,
            'countF' => $countF,
            'countV' => $countV,
            'totalBasketball' => $totalBasketball,
            'totalFootball' => $totalFootball,
            'totalVolleyball' => $totalVolleyball
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

	        $query->select('name, id, type_id')
	              ->from('court')
	              ->where(['district_city_id' => $id])
                  ->andWhere('approved = 0');
	        $rows = $query->all();

	        
	        // var_dump($countBookmark);

	    $string = '<tr>
						<th>Название площадки</th>
                        <th>Спорт</th>
						<th>Число сыгранных игр</th>
						<th>Подписанных участников</th>
					</tr>
					<tr>
						<td colspan="4">'.$distr['name'].' район</td>
					</tr>';
		$i = 0;
	    foreach ($rows as $item) {
	    	$query->select('count(user_id) as count')
	              ->from('court_bookmark')
	              ->where(['court_id' => $item['id']]);
	        $countBookmark = $query->all();

            $queryS = new Query;
            $queryS->select('name')
                  ->from('sport_type')
                  ->where(['id' => $item['type_id']]);
            $sport_type = $queryS->one();


	        $query->select('count(id) as count')
	              ->from('game')
	              ->where(['court_id' => $item['id']])
	              ->andWhere(['<','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')))]);
	        $countGame = $query->all();

	        // var_dump($countBookmark);

	    	$string = $string.'<tr>
									<td><a target="_blank" href="/court/'.$item['id'].'">'.$item['name'].'</a></td>
                                    <td>'.$sport_type['name'].'</td>
									<td>'.$countGame[0]['count'].'</td>
									<td>'.$countBookmark[0]['count'].'</td>
								</tr>';
			$i++;
	    }
    	// echo $string;
        return $string;
    }

    public function actionCourtmod()
    {
        $court = Yii::createObject(Court::className());
        $courts = $court->find()->where('approved = 1')->all();

        $distr = Yii::createObject(DistrictCity::className());
        $districts = $distr->find()->all();

        $sport = Yii::createObject(SportType::className());
        $sportType = $sport->find()->all();

        $courtsName = [];
        $courtsSport = [];
        $i = 0;
        foreach ($courts as $item) {
            $query = new Query;
            $query->select('name')
                  ->from('district_city')
                  ->where(['id' => $item['district_city_id']]);
            $row = $query->one();

            $query->select('name')
                  ->from('sport_type')
                  ->where(['id' => $item['type_id']]);
            $rowSport = $query->one();

            $courtsName[$i] = $row['name'];
            $courtsSport[$i] = $rowSport['name'];
            $i++;
        }

// var_dump($courtsName);


        return $this->render('courtmod',[
            'courts' => $courts,
            'districts' => $districts,
            'sportType' => $sportType,
            'courtsName' => $courtsName,
            'courtsSport' => $courtsSport,
        ]);
    }

    public function actionDelete_court()
    {
        $id = Yii::$app->getRequest()->getBodyParam("id");
        $tr = Yii::$app->getRequest()->getBodyParam("tr");

        $court = Yii::createObject(Court::className());
        $courtDel = $court->findOne(['id' => $id]);
        
        // echo $photo->delete();
        if($courtDel->delete())
        {
            return $tr;
        }
        else
            return 'ошибка';
    }

    public function actionAdd_court()
    {
        $id = Yii::$app->getRequest()->getBodyParam("id");
        $tr = Yii::$app->getRequest()->getBodyParam("tr");
        $address = Yii::$app->getRequest()->getBodyParam("address");
        $name = Yii::$app->getRequest()->getBodyParam("name");
        $area = Yii::$app->getRequest()->getBodyParam("area");
        $district = Yii::$app->getRequest()->getBodyParam("district");
        $type = Yii::$app->getRequest()->getBodyParam("type");
        $lat = Yii::$app->getRequest()->getBodyParam("lat");
        $lon = Yii::$app->getRequest()->getBodyParam("lon");
        $description = Yii::$app->getRequest()->getBodyParam("description");
        
        $court = Yii::createObject(Court::className());
        $courtAdd = $court->findOne(['id' => $id]);

        $courtAdd->approved = 0;
        $courtAdd->address = $address;
        $courtAdd->lat = $lat;
        $courtAdd->lon = $lon;
        $courtAdd->name = $name;
        $courtAdd->built_up_area = $area;
        $courtAdd->district_city_id = $district;
        $courtAdd->type_id = $type;
        $courtAdd->description = $description;


        if($courtAdd->save())
        {
            return $tr;
        }
        else
            return 'ошибка';
    }

    public function actionPhotouser()
    {
            $query = new Query;
            $query->select('id, username, picture')
                  ->from('user, profile')
                  ->where('id = user_id');
            $users = $query->all();

        return $this->render('photouser',[
            'users' => $users,
        ]);
    }

    public function actionDelete_avatar()
    {
        $id = Yii::$app->getRequest()->getBodyParam("id");
        $tr = Yii::$app->getRequest()->getBodyParam("tr");
        $picture = Yii::$app->getRequest()->getBodyParam("picture");

        $profile = Yii::createObject(Profile2::className());
        $avatarDel = $profile->find()->where(['user_id' => $id])->one();

        $avatarDel->picture = 'default_avatar.jpg';

        // добавить удаление файла

        if($avatarDel->save())
            return $tr;
        else
            return 'ошибка';
    }

    public function actionPopularcourts()
    {
        $queryBas = new Query;
        $queryBas->select('court.id as id, address, court.name as name, district_city.name as nameDistrict, count(game.id) as count')
              ->from('court, game, district_city')
              ->where('type_id = 1')
              ->andWhere('court.id = court_id')
              ->andWhere('district_city.id = district_city_id')
              ->groupBy('court.id')
              ->orderBy('count desc')
              ->limit(5);
        $rowsBas = $queryBas->all();

        $queryFoot = new Query;
        $queryFoot->select('court.id as id, address, court.name as name, district_city.name as nameDistrict, count(game.id) as count')
              ->from('court, game, district_city')
              ->where('type_id = 2')
              ->andWhere('court.id = court_id')
              ->andWhere('district_city.id = district_city_id')
              ->groupBy('court.id')
              ->orderBy('count desc')
              ->limit(5);
        $rowsFoot = $queryFoot->all();

        $queryVball = new Query;
        $queryVball->select('court.id as id, address, court.name as name, district_city.name as nameDistrict, count(game.id) as count')
              ->from('court, game, district_city')
              ->where('type_id = 3')
              ->andWhere('court.id = court_id')
              ->andWhere('district_city.id = district_city_id')
              ->groupBy('court.id')
              ->orderBy('count desc')
              ->limit(5);
        $rowsVball = $queryVball->all();

        $queryBasLeast = new Query;
        $queryBasLeast->select('court.id as id, address, court.name as name, district_city.name as nameDistrict, count(game.id) as count')
              ->from('court, game, district_city')
              ->where('type_id = 1')
              ->andWhere('court.id = court_id')
              ->andWhere('district_city.id = district_city_id')
              ->groupBy('court.id')
              ->orderBy('count')
              ->limit(5);
        $rowsBasLeast = $queryBasLeast->all();

        $queryFootLeast = new Query;
        $queryFootLeast->select('court.id as id, address, court.name as name, district_city.name as nameDistrict, count(game.id) as count')
              ->from('court, game, district_city')
              ->where('type_id = 2')
              ->andWhere('court.id = court_id')
              ->andWhere('district_city.id = district_city_id')
              ->groupBy('court.id')
              ->orderBy('count')
              ->limit(5);
        $rowsFootLeast = $queryFootLeast->all();

        $queryVballLeast = new Query;
        $queryVballLeast->select('court.id as id, address, court.name as name, district_city.name as nameDistrict, count(game.id) as count')
              ->from('court, game, district_city')
              ->where('type_id = 3')
              ->andWhere('court.id = court_id')
              ->andWhere('district_city.id = district_city_id')
              ->groupBy('court.id')
              ->orderBy('count')
              ->limit(5);
        $rowsVballLeast = $queryVballLeast->all();
        // var_dump($rowsBas);

        return $this->render('popularcourts',[
            'rowsBas' => $rowsBas,
            'rowsFoot' => $rowsFoot,
            'rowsVball' => $rowsVball,
            'rowsBasLeast' => $rowsBasLeast,
            'rowsFootLeast' => $rowsFootLeast,
            'rowsVballLeast' => $rowsVballLeast
        ]);
    }

    public function actionActivity()
    {
        $now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
        $query = new Query;
        $query->select('district_city.id as id, district_city.name as name, count(game.id) as count')
              ->from('court, game, district_city')
              ->andWhere('district_city.id = district_city_id')
              ->andWhere('court.id = court_id')
              ->andWhere('approved = 0');

        $query->andWhere(['<','time',$now])    
              ->groupBy('district_city.id')
              ->orderBy('count desc');
        $rows = $query->all();

        $queryBas = new Query;
        $queryBas->select('district_city.id as id, district_city.name as name, count(game.id) as count')
              ->from('court, game, district_city')
              ->andWhere('district_city.id = district_city_id')
              ->andWhere('court.id = court_id')
              ->andWhere('type_id = 1')
              ->andWhere('approved = 0');

        $queryBas->andWhere(['<','time',$now])    
              ->groupBy('district_city.id')
              ->orderBy('count desc');
        $rowsBas = $queryBas->all();

        $queryFoot = new Query;
        $queryFoot->select('district_city.id as id, district_city.name as name, count(game.id) as count')
              ->from('court, game, district_city')
              ->andWhere('district_city.id = district_city_id')
              ->andWhere('court.id = court_id')
              ->andWhere('type_id = 2')
              ->andWhere('approved = 0');

        $queryFoot->andWhere(['<','time',$now])    
              ->groupBy('district_city.id')
              ->orderBy('count desc');
        $rowsFoot = $queryFoot->all();

        $queryVball = new Query;
        $queryVball->select('district_city.id as id, district_city.name as name, count(game.id) as count')
              ->from('court, game, district_city')
              ->andWhere('district_city.id = district_city_id')
              ->andWhere('court.id = court_id')
              ->andWhere('type_id = 3')
              ->andWhere('approved = 0');

        $queryVball->andWhere(['<','time',$now])    
              ->groupBy('district_city.id')
              ->orderBy('count desc');
        $rowsVball = $queryVball->all();

        return $this->render('activity',[
            'rows' => $rows,
            'rowsBas' => $rowsBas,
            'rowsFoot' => $rowsFoot,
            'rowsVball' => $rowsVball
        ]);
    }
    public function actionActivityuser()
    {
        $now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
        $query = new Query;
        $query->select('user.id as id, username, count(game_user.id) as count')
              ->from('user, game_user')
              ->andWhere('user.id = user_id')  
              ->groupBy('user.id')
              ->orderBy('count desc')
              ->limit(10);
        $rows = $query->all();

        return $this->render('activityuser',[
            'rows' => $rows
        ]);
    }
    public function actionReport_courts()
    {
        $reportObject = Yii::createObject(Report::className());
        $report = $reportObject->find()->all();
        return $this->render('report_courts',[
            'report' => $report,
        ]);
    }
}
