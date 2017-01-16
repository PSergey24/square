<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use app\models\Court;
use app\models\SportType;
use app\models\GameUser;
use yii\web\Response;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\forms\GameCreateForm;
use DateTime;
use DateInterval;

/**
 * GameController implements the CRUD actions for Game model.
 */
class GameController extends Controller
{
    const SUCCESS_CREATE_BOX =
        '<i class="fa fa-times close fa-lg" aria-hidden="true" data-dismiss="modal" ></i>
            <i class="fa fa-check fa-4x ok" aria-hidden="true"></i>';
    const ERROR_CREATE_BOX =
        '<i class="fa fa-times close fa-lg" aria-hidden="true" data-dismiss="modal" ></i>
            <i class="fa fa-times fa-4x" aria-hidden="true"></i>';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Game models.
     * @return mixed
     */
    public function actionIndex()
    {
        $nameSportArr = array();
        $nameAreaArr = array();
        $idUsersArr = array();
        $countUsersArr = array();
        $pictureUsersArr = array();
        $plusMan = array();

        // получаем id авторизованного пользователя
        if(Yii::$app->user->identity)
            $userAuth = Yii::$app->user->identity->getId();
        else
            $userAuth = 0;


        $dataProvider = new ActiveDataProvider([
            'query' => Game::find(),
        ]);

        $listGame = Game::find()->where(['>=', 'time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))])->limit(6)->orderBy('time')->all();
        $numGame = sizeof($listGame);
        $gameArray = '';
        foreach ($listGame as $itemGame) {
            $pictureUserArr = array();
            $nameArea = Court::find()
                        ->where(['id' => $itemGame['court_id']])
                        ->one();
            array_push($nameAreaArr,$nameArea['name']);

            $nameSport = SportType::find()
                        ->where(['id' => $itemGame['sport_type_id']])
                        ->one();

            $gameArray = $gameArray.$itemGame['id']." ";

            $query = new Query;
            $idUser = $query->select('user_id')->from('game_user')->where(['game_id' => $itemGame['id']])->all();

            $p = 0;          
            foreach($idUser as $id){
                $queryPicture = new Query;
                $pictureUser = $queryPicture->select('picture')->from('profile')->where(['user_id' => $id])->one();
                array_push($pictureUserArr,$pictureUser);
                if($id['user_id'] == $userAuth)
                    $p = 1;
            }
            if($p == 1)
                array_push($plusMan,'-');
            else
                array_push($plusMan,'+');
            

            $countUser = count($idUser);
            array_push($pictureUsersArr,$pictureUserArr);
            array_push($countUsersArr,$countUser);
            array_push($idUsersArr,$idUser);
            array_push($nameSportArr,$nameSport['name']);
        }
        $gameArray = trim($gameArray);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'listGame' => $listGame,
            'nameSportArr' => $nameSportArr,
            'nameAreaArr' => $nameAreaArr,
            'numGame' => $numGame,
            'countUsersArr' => $countUsersArr,
            'idUsersArr' => $idUsersArr,
            'pictureUsersArr' => $pictureUsersArr,
            'plusMan' => $plusMan,
            'gameArray' => $gameArray,
        ]);
    }

    /**
     * Displays a single Game model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionMore()
    {
        $numGame = Yii::$app->getRequest()->getBodyParam("numGame");
        $dataSport = Yii::$app->getRequest()->getBodyParam("dataSport");
        $timeFilter = Yii::$app->getRequest()->getBodyParam("timeFilter");
        $nearFilter = Yii::$app->getRequest()->getBodyParam("nearFilter");
        $peopleFilter = Yii::$app->getRequest()->getBodyParam("peopleFilter");
        $districtFilter = Yii::$app->getRequest()->getBodyParam("districtFilter");

        // получаем id авторизованного пользователя
        if(Yii::$app->user->identity)
            $userAuth = Yii::$app->user->identity->getId();
        else
            $userAuth = 0;

        if($nearFilter == 'no'){

            if($peopleFilter != 'no')
            {
                $pieces = explode("-", $peopleFilter);
                $min = $pieces[0];
                $max = $pieces[1];


                $query = new Query;
                        

                    if($districtFilter != 0){
                        $query->select('game.id as gameId, time, need_ball, sport_type_id, court_id, game_user.id as idGameUser, game_id, user_id, court.id as courtId, address, name, district_city_id,COUNT(game_id)')
                              ->from('game,court,game_user')
                              ->andWhere('court.id = game.court_id')
                              ->andWhere(['court.district_city_id' => $districtFilter])
                              ->andWhere('game.id = game_user.game_id');
                    }else{
                        $query->select('game.id as gameId, time, need_ball, sport_type_id, court_id, game_user.id as idGameUser, game_id, user_id,COUNT(game_id)')
                              ->from('game,game_user')
                              ->andWhere('game.id = game_user.game_id');
                    }
                    
                if($dataSport != 0)
                    $query->andWhere(['sport_type_id' => $dataSport]);

                    if($timeFilter == 'no')
                        $query->andWhere(['>=','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))]);
                    elseif($timeFilter == 'Сегодня'){
                        $now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'));
                        $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
                        $query->andWhere(['>=','time',$now])->andWhere(['<','time',$tomorrow]);
                    }
                    elseif($timeFilter == 'Завтра')
                    {
                        $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
                        $afterTomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+2, date("Y")));
                        $query->andWhere(['>=','time',$tomorrow])->andWhere(['<','time',$afterTomorrow]);
                    }

                    $query->groupBy('game.id');

                    if($min != 0)
                        $query->having(['>=','COUNT(game_id)',$min]);
                    else{
                        if($max != 0)
                            $query->having(['<=','COUNT(game_id)',$max]);
                    }

                    if($max != 0)
                        $query->andHaving(['<=','COUNT(game_id)',$max]);

                $listGame = $query->offset($numGame)->limit(6)->orderBy('time')->all();
            }else{


                $query = new Query;
                

                    if($districtFilter != 0){
                        $query->select('game.id as gameId, time, need_ball, sport_type_id, court_id, court.id as courtId, address, name, district_city_id')
                              ->from('game,court')
                              ->andWhere('court.id = game.court_id')
                              ->andWhere(['court.district_city_id' => $districtFilter]);
                    }else{
                        $query->select('game.id as gameId, time, need_ball, sport_type_id, court_id')
                              ->from('game');
                    }
           
                if($timeFilter == 'no')
                    $query->andWhere(['>=','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))]);
                elseif($timeFilter == 'Сегодня'){
                    $now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'));
                    $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
                    $query->andWhere(['>=','time',$now])->andWhere(['<','time',$tomorrow]);
                }
                elseif($timeFilter == 'Завтра')
                {
                    $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
                    $afterTomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+2, date("Y")));
                    $query->andWhere(['>=','time',$tomorrow])->andWhere(['<','time',$afterTomorrow]);
                }

                if($dataSport != 0) 
                    $query->andWhere(['sport_type_id' => $dataSport]);
         
                $listGame = $query->offset($numGame)->limit(6)->orderBy('time')->all();
            }


            $string = '';

            $gameArray = '';
            foreach ($listGame as $thisGame) {
                $area = Court::find()
                            ->where(['id' => $thisGame['court_id']])
                            ->one();

                $sport = SportType::find()
                            ->where(['id' => $thisGame['sport_type_id']])
                            ->one();

                if($thisGame['need_ball'] == 1)
                    $ball = 'Есть';
                else
                    $ball = 'Нет';

                $gameArray = $gameArray.$thisGame['gameId']." ";

                $classSport = '';
                if($thisGame['sport_type_id'] == 1)
                    $classSport = 'basketball';
                elseif($thisGame['sport_type_id'] == 2)
                    $classSport = 'football';

                if(date_format(date_create($thisGame['time']), 'd') == (date("d")+1))
                    $timeGame =  'завтра '.date_format(date_create($thisGame['time']), 'H:i');
                elseif(date_format(date_create($thisGame['time']), 'd') == (date("d")))
                    $timeGame =  'сегодня '.date_format(date_create($thisGame['time']), 'H:i');
                else
                    $timeGame =  date_format(date_create($thisGame['time']), 'd-m H:i');


                $queryPeoples = new Query;
                $idUser = $queryPeoples->select('user_id')->from('game_user')->where(['game_id' => $thisGame['gameId']])->all();
                $countUser2 = count($idUser);
                $str = '';
                $p = 0;
                foreach($idUser as $id){
                    $queryPicture = new Query;
                    $pictureUser = $queryPicture->select('picture')->from('profile')->where(['user_id' => $id])->one();
                    $str = $str.'<a href="#"><img src="/img/uploads/'.$pictureUser['picture'].'" class="man"></a>';
                    if($id['user_id'] == $userAuth)
                        $p = 1;
                }
                if($p == 1)
                    $plusMan = '-';
                else
                    $plusMan = '+';
                
                $string = $string.'
                    <div class="col-xs-12 col-lg-6 first" data-id-game="'.$thisGame['gameId'].'">
                                <div class="shadow box game-new '.$classSport.'" >
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="top">
                                            <div class="square">'. $area['name'] .'</div>
                                            <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true"></i></div>
                                        </div>
                                        <div id="maps" class="visible-xs"><!--КАРТА ДЛЯ ТЕЛЕФОНА-->
                                            
                                        </div>
                                        <div class="divider"></div>
                                        <div class="people">
                                            <p>Игроков: <span class="count">'.$countUser2.'</span></p>
                                            <div class="scroll">
                                                <div class="right"></div>
                                                <div class="circle">
                                                    <div class="plus man"  data-id-game-plus="'.$thisGame['gameId'].'" onclick="people('.$thisGame['gameId'].',\''.$plusMan.'\')"><span>'.$plusMan.'</span></div>'.$str.'
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="type">
                                            <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                                            <span class="big">'. $sport['name'] .'</span>
                                        </div>
                                        <div class="time">
                                            <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                                            <span class="big">'. $timeGame .'</span>
                                        </div>
                                        <div class="ball">
                                            <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                                            <span class="big">'. $ball .'</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            }  
            $count = count($listGame);
            $gameArray = trim($gameArray);
            return $count." | ".$string." | ".$gameArray;
        }
    }

    public function actionReset()
    {
        $gameList = Game::find()->where(['>=', 'time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))])->limit(6)->orderBy('time')->all();

        // получаем id авторизованного пользователя
        if(Yii::$app->user->identity)
            $userAuth = Yii::$app->user->identity->getId();
        else
            $userAuth = 0;
    
        $gameArray = '';

        $string = '';
        foreach ($gameList as $game) {
            $area = Court::find()
                        ->where(['id' => $game['court_id']])
                        ->one();

            $sport = SportType::find()
                        ->where(['id' => $game['sport_type_id']])
                        ->one();

            if($game['need_ball'] == 1)
                $ball = 'Есть';
            else
                $ball = 'Нет';

            $gameArray = $gameArray.$game['id']." ";



            $classSport = '';
            if($game['sport_type_id'] == 1)
                $classSport = 'basketball';
            elseif($game['sport_type_id'] == 2)
                $classSport = 'football';

            if(date_format(date_create($game['time']), 'd') == (date("d")+1))
                $timeGame =  'завтра '.date_format(date_create($game['time']), 'H:i');
            elseif(date_format(date_create($game['time']), 'd') == (date("d")))
                $timeGame =  'сегодня '.date_format(date_create($game['time']), 'H:i');
            else
                $timeGame =  date_format(date_create($game['time']), 'd-m H:i');

            $queryPeoples = new Query;
            $idUser = $queryPeoples->select('user_id')->from('game_user')->where(['game_id' => $game['id']])->all();
            $countUser2 = count($idUser);
            $str = '';

            $p = 0;
            foreach($idUser as $id){
                $queryPicture = new Query;
                $pictureUser = $queryPicture->select('picture')->from('profile')->where(['user_id' => $id])->one();
                $str = $str.'<a href="#"><img src="/img/uploads/'.$pictureUser['picture'].'" class="man"></a>';
                if($id['user_id'] == $userAuth)
                        $p = 1;
            }
                if($p == 1)
                    $plusMan = '-';
                else
                    $plusMan = '+';

            $string = $string.'
                <div class="col-xs-12 col-lg-6 first" data-id-game="'.$game['id'].'">
                            <div class="shadow box game-new '.$classSport.'" >
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="top">
                                        <div class="square">'. $area['name'] .'</div>
                                        <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true"></i></div>
                                    </div>
                                    <div id="maps" class="visible-xs"><!--КАРТА ДЛЯ ТЕЛЕФОНА-->
                                        
                                    </div>
                                    <div class="divider"></div>
                                    <div class="people">
                                        <p>Игроков: <span class="count">'.$countUser2.'</span></p>
                                        <div class="scroll">
                                            <div class="right"></div>
                                            <div class="circle">
                                                <div class="plus man"  data-id-game-plus="'.$game['id'].'" onclick="people('.$game['id'].',\''.$plusMan.'\')"><span>'.$plusMan.'</span></div>'.$str.'
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="type">
                                        <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                                        <span class="big">'. $sport['name'] .'</span>
                                    </div>
                                    <div class="time">
                                        <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                                        <span class="big">'. $timeGame .'</span>
                                    </div>
                                    <div class="ball">
                                        <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                                        <span class="big">'. $ball .'</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
        }  
        $count = count($gameList);
        $gameArray = trim($gameArray);

        return $count." | ".$string." | ".$gameArray;
    }

    public function actionNear()
    {
        $latCenter = Yii::$app->getRequest()->getBodyParam("lat");
        $lonCenter = Yii::$app->getRequest()->getBodyParam("lon");
        // $latCenter = 59.9830;
        // $lonCenter = 30.412;
        $radius = 0.01; // 1,1км
        $rSum = $radius*$radius;

        // получаем id авторизованного пользователя
        if(Yii::$app->user->identity)
            $userAuth = Yii::$app->user->identity->getId();
        else
            $userAuth = 0;

        $query = new Query;
        $query->select('game.id as gameId, time, need_ball, sport_type_id, court_id, court.id as courtId, address, lat, lon, name, district_city_id')
              ->from('game,court')
              ->andWhere('court.id = game.court_id')
              ->andWhere(['>=','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))]);

        $listGame = $query->orderBy('time')->all();

        $gameArray = '';
        $string = '';
        foreach ($listGame as $list) {
            $summa = 0;
            $latSum = ($latCenter - $list['lat'])*($latCenter - $list['lat']);
            $lonSum = ($lonCenter - $list['lon'])*($lonCenter - $list['lon']);

            $summa = $latSum + $lonSum;
            if($summa <= $rSum){
                $area = Court::find()
                            ->where(['id' => $list['court_id']])
                            ->one();

                $sport = SportType::find()
                            ->where(['id' => $list['sport_type_id']])
                            ->one();

                if($list['need_ball'] == 1)
                    $ball = 'Есть';
                else
                    $ball = 'Нет';

                $gameArray = $gameArray.$list['gameId']." ";

                $styleSport = '';

                if($list['sport_type_id'] == 1)
                    $styleSport = 'basketball';
                elseif($list['sport_type_id'] == 2)
                    $styleSport = 'football';

                if(date_format(date_create($list['time']), 'd') == (date("d")+1))
                    $timeGame =  'завтра '.date_format(date_create($list['time']), 'H:i');
                elseif(date_format(date_create($list['time']), 'd') == (date("d")))
                    $timeGame =  'сегодня '.date_format(date_create($list['time']), 'H:i');
                else
                    $timeGame =  date_format(date_create($list['time']), 'd-m H:i');


                $queryPeoples = new Query;
                $idUser = $queryPeoples->select('user_id')->from('game_user')->where(['game_id' => $list['gameId']])->all();
                $countUser2 = count($idUser);
                $str = '';
                $p = 0;
                foreach($idUser as $id){
                    $queryPicture = new Query;
                    $pictureUser = $queryPicture->select('picture')->from('profile')->where(['user_id' => $id])->one();
                    $str = $str.'<a href="#"><img src="/img/uploads/'.$pictureUser['picture'].'" class="man"></a>';
                    if($id['user_id'] == $userAuth)
                        $p = 1;
                }
                if($p == 1)
                    $plusMan = '-';
                else
                    $plusMan = '+';

                 $string = $string.'
                    <div class="col-xs-12 col-lg-6 first" data-id-game="'.$list['gameId'].'">
                                <div class="shadow box game-new '.$styleSport.'" >
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="top">
                                            <div class="square">'. $area['name'] .'</div>
                                            <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true"></i></div>
                                        </div>
                                        <div id="maps" class="visible-xs"><!--КАРТА ДЛЯ ТЕЛЕФОНА-->
                                            
                                        </div>
                                        <div class="divider"></div>
                                        <div class="people">
                                            <p>Игроков: <span class="count">'.$countUser2.'</span></p>
                                            <div class="scroll">
                                                <div class="right"></div>
                                                <div class="circle">
                                                    <div class="plus man" data-id-game-plus="'.$list['gameId'].'" onclick="people('.$list['gameId'].',\''.$plusMan.'\')"><span>'.$plusMan.'</span></div>'.$str.'
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="type">
                                            <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                                            <span class="big">'. $sport['name'] .'</span>
                                        </div>
                                        <div class="time">
                                            <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                                            <span class="big">'. $timeGame .'</span>
                                        </div>
                                        <div class="ball">
                                            <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                                            <span class="big">'. $ball .'</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            }
        } 
        $gameArray = trim($gameArray); 
        return $string." | ".$gameArray;
    }

    

    public function actionApply()
    {
        $typeSport = Yii::$app->getRequest()->getBodyParam("typeSport");
        $timeFilter = Yii::$app->getRequest()->getBodyParam("timeFilter");
        $peopleFilter = Yii::$app->getRequest()->getBodyParam("peopleFilter");
        $districtFilter = Yii::$app->getRequest()->getBodyParam("districtFilter");

        // получаем id авторизованного пользователя
        if(Yii::$app->user->identity)
            $userAuth = Yii::$app->user->identity->getId();
        else
            $userAuth = 0;

        if($peopleFilter != 'no')
        {
            $pieces = explode("-", $peopleFilter);
            $min = $pieces[0];
            $max = $pieces[1];


                $query = new Query;

                if($districtFilter != 0){
                    $query->select('game.id as gameId, time, need_ball, sport_type_id, court_id, game_user.id as idGameUser, game_id, user_id, court.id as courtId, address, name, district_city_id,COUNT(game_id)')
                          ->from('game,game_user,court')
                          ->andWhere('court.id = game.court_id')
                          ->andWhere(['court.district_city_id' => $districtFilter])
                          ->andWhere('game.id = game_user.game_id');
                    
                }else{
                    $query->select('game.id as gameId, time, need_ball, sport_type_id, court_id,, game_user.id as idGameUser, game_id, user_id,COUNT(game_id)')
                          ->from('game,game_user')
                          ->andWhere('game.id = game_user.game_id');
                }
                    
                if ($typeSport != 0) 
                    $query->andWhere(['sport_type_id' => $typeSport]);

                if($timeFilter == 'no')
                    $query->andWhere(['>=','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))]);
                elseif($timeFilter == 'Сегодня'){
                    $now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'));
                    $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
                    $query->andWhere(['>=','time',$now])->andWhere(['<','time',$tomorrow]);
                }
                elseif($timeFilter == 'Завтра')
                {
                    $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
                    $afterTomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+2, date("Y")));
                    $query->andWhere(['>=','time',$tomorrow])->andWhere(['<','time',$afterTomorrow]);
                }

                $query->groupBy('game.id');

                if($min != 0)
                    $query->having(['>=','COUNT(game_id)',$min]);
                else{
                    if($max != 0)
                        $query->having(['<=','COUNT(game_id)',$max]);
                }

                if($max != 0)
                    $query->andHaving(['<=','COUNT(game_id)',$max]);

            $listGame = $query->limit(6)->orderBy('time')->all();

        }else
        {
            $query = new Query;

                if($districtFilter != 0){
                    $query->select('game.id as gameId, time, need_ball, sport_type_id, court_id, court.id as courtId, address, name, district_city_id')
                          ->from('game,court')
                          ->andWhere('court.id = game.court_id')
                          ->andWhere(['court.district_city_id' => $districtFilter]);
                }else{
                    $query->select('game.id as gameId, time, need_ball, sport_type_id, court_id')
                          ->from('game');
                }

       
            if($timeFilter == 'no')
                $query->andWhere(['>=','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))]);
            elseif($timeFilter == 'Сегодня'){
                $now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'));
                $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
                $query->andWhere(['>=','time',$now])->andWhere(['<','time',$tomorrow]);
            }
            elseif($timeFilter == 'Завтра')
            {
                $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")));
                $afterTomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d")+2, date("Y")));
                $query->andWhere(['>=','time',$tomorrow])->andWhere(['<','time',$afterTomorrow]);
            }

            if($typeSport != 0) 
                $query->andWhere(['sport_type_id' => $typeSport]);
     
            $listGame = $query->limit(6)->orderBy('time')->all();
        }
       
        $string = '';
        $gameArray = '';
        foreach ($listGame as $thisGame) {
            $area = Court::find()
                        ->where(['id' => $thisGame['court_id']])
                        ->one();

                $sport = SportType::find()
                            ->where(['id' => $thisGame['sport_type_id']])
                            ->one();

            $gameArray = $gameArray.$thisGame['gameId']." ";

            if($thisGame['need_ball'] == 1)
                $ball = 'Есть';
            else
                $ball = 'Нет';

            $classSport = '';
                if($thisGame['sport_type_id'] == 1)
                    $classSport = 'basketball';
                elseif($thisGame['sport_type_id'] == 2)
                    $classSport = 'football';

            if(date_format(date_create($thisGame['time']), 'd') == (date("d")+1))
                $timeGame =  'завтра '.date_format(date_create($thisGame['time']), 'H:i');
            elseif(date_format(date_create($thisGame['time']), 'd') == (date("d")))
                $timeGame =  'сегодня '.date_format(date_create($thisGame['time']), 'H:i');
            else
                $timeGame =  date_format(date_create($thisGame['time']), 'd-m H:i');

            // return $thisGame['id'];
            $queryPeoples = new Query;
            $idUser = $queryPeoples->select('user_id')->from('game_user')->where(['game_id' => $thisGame['gameId']])->all();
            // var_dump($idUser);
            $countUser2 = count($idUser);
            $str = '';
            $p = 0;
            foreach($idUser as $id){
                $queryPicture = new Query;
                $pictureUser = $queryPicture->select('picture')->from('profile')->where(['user_id' => $id])->one();
                $str = $str.'<a href="#"><img src="/img/uploads/'.$pictureUser['picture'].'" class="man"></a>';
                if($id['user_id'] == $userAuth)
                    $p = 1;
            }
            if($p == 1)
                $plusMan = '-';
            else
                $plusMan = '+';

            $string = $string.'
                <div class="col-xs-12 col-lg-6 first" data-id-game="'.$thisGame['gameId'].'">
                            <div class="shadow box game-new '.$classSport.'" >
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="top">
                                        <div class="square">'. $area['name'] .'</div>
                                        <div class="onmap"><i class="fa fa-globe fa-lg" aria-hidden="true"></i></div>
                                    </div>
                                    <div id="maps" class="visible-xs"><!--КАРТА ДЛЯ ТЕЛЕФОНА-->
                                        
                                    </div>
                                    <div class="divider"></div>
                                    <div class="people">
                                        <p>Игроков: <span class="count">'.$countUser2.'</span></p>
                                        <div class="scroll">
                                            <div class="right"></div>
                                            <div class="circle">
                                                <div class="plus man" data-id-game-plus="'.$thisGame['gameId'].'" onclick="people('.$thisGame['gameId'].',\''.$plusMan.'\')"><span>'.$plusMan.'</span></div>'.$str.'
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="description col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="type">
                                        <span class="small"><i class="fa fa-dribbble" aria-hidden="true"></i>Игра</span><br>
                                        <span class="big">'. $sport['name'] .'</span>
                                    </div>
                                    <div class="time">
                                        <span class="small"><i class="fa fa-clock-o" aria-hidden="true"></i>Время</span><br>
                                        <span class="big">'. $timeGame .'</span>
                                    </div>
                                    <div class="ball">
                                        <span class="small"><i class="fa fa-futbol-o" aria-hidden="true"></i>Мяч</span><br>
                                        <span class="big">'. $ball .'</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
        }  
        $count = count($listGame);
        $gameArray = trim($gameArray);
        return $count." | ".$string." | ".$gameArray;
    }

    public function actionPlayer()
    {
        $game = Yii::$app->getRequest()->getBodyParam("game");
        $symbol = Yii::$app->getRequest()->getBodyParam("symbol");
        // $symbol = '+';
        // $game = 29;
        // return $symbol;
        // получаем id авторизованного пользователя
        if(Yii::$app->user->identity)
            $userAuth = Yii::$app->user->identity->getId();
        else
            return 'Вы не авторизованы';

        $gameUser = Yii::createObject(GameUser::className());
        $gameUser->game_id = $game;
        $gameUser->user_id = $userAuth;

        $string = '';
        if($symbol == '-')
        {
            $player = $gameUser->findOne([
                'game_id' => $gameUser->game_id,
                'user_id' => $gameUser->user_id
            ]);
            $player->delete();
        }else{
            $gameUser->save();
        }
                $query = new Query;
                $query->select('user_id')
                      ->from('game_user')
                      ->andWhere(['game_id' => $game]);
                $players = $query->all();
                $count = count($players);

                if($count == 0){
                    $gameItem = Yii::createObject(Game::className());
                    $gameItem = $gameItem->findOne([
                        'id' => $gameUser->game_id
                    ]);
                    $gameItem->delete();
                }

                foreach ($players as $man) {
                    $queryMan = new Query;
                    $queryMan->select('picture')
                          ->from('profile')
                          ->andWhere(['user_id' => $man]);
                    $picture = $queryMan->one();

                    $string = $string.'<a href="#"><img src="/img/uploads/'.$picture['picture'].'" class="man"></a>';
                }
                // echo $string;
                return $game.'|'.$count.'|'.$symbol.'|'.$string;
    }

    /**
     * Creates a new Game model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isPjax) {

            $model = Yii::createObject(GameCreateForm::className());

            $model->load(Yii::$app->request->post());

            if (!$model->creator_id)
                throw new UserException('Для того чтобы создать игру необходимо авторизоваться');

            $date = new DateTime();
            if ($model->day) {
                $date->add(new DateInterval('P1D'));
            }

            $time = $model->time_digit;
            $datetime = date_format($date, 'Y-m-d') . ' ' . $time;
            $model->time = $datetime;

            //check if game already exist
            if (Game::findOne(['court_id' => $model->court_id,'time' => $model->time]))
                return self::ERROR_CREATE_BOX . '<p id="warning">Игра уже существует</p>';

            $model->save();

            // Создается запись в таблице game_user
            $model2 = Yii::createObject(GameUser::className());
            $model2->user_id = $model->creator_id;
            $model2->game_id = $model->id;
            $model2->save();

            return self::SUCCESS_CREATE_BOX  . '<p id="warning">Игра успешно создана</p>';

        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGet_markers()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        // получаем id авторизованного пользователя
        if(Yii::$app->user->identity)
            $userAuth = Yii::$app->user->identity->getId();
        else
            $userAuth = 0;

        $query = new Query;
        $query->select('court.id as courtId, address, lat, lon, name, game.id as gameId, time, sport_type_id, court_id, game_id, user_id, count(game_id) as count')
            ->from('court,game, game_user')
            ->andWhere('court_id = court.id')
            ->andWhere('game_id = game.id')
            ->groupBy('game.id');
        $rows = $query->all();
        $arr = '';
        $i = 0;
        foreach($rows as $row){
            $queryPeoples = new Query;
            $idUser = $queryPeoples->select('user_id')->from('game_user')->where(['game_id' => $row['gameId']])->all();
            $countUser2 = count($idUser);
            $str = '';
            $plusMinus = '+';
            foreach($idUser as $id){
                $queryPicture = new Query;
                $pictureUser = $queryPicture->select('picture')->from('profile')->where(['user_id' => $id])->one();
                $str = $str.'<a href="#"><img src="/img/uploads/'.$pictureUser['picture'].'" class="man"></a>';

                if($id['user_id'] == $userAuth)
                    $plusMinus = '-';
            }
            // echo $userAuth." -- ".$plusMinus."\n";
            $rows[$i]['string'] = $str;
            $rows[$i]['plus'] = $plusMinus;
            $i++;
        }
        return $rows;
    }

    /**
     * Updates an existing Game model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Deletes an existing Game model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Game model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Game the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Game::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
