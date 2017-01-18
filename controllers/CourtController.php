<?php

namespace app\controllers;

use app\models\CourtLikes;
use app\models\MapFilters;
use app\models\SportType;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\CourtBookmark;
use app\models\CourtType;
use app\models\Court;
use app\models\Game;
use app\models\GameUser;
use app\models\DistrictCity;
use app\models\forms\GameCreateForm;
use app\custom\HTMLSelectData;

/**
 * CourtController implements the CRUD actions for Court model.
 */
class CourtController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access_create' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'access_update_delete' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Court models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $popular = Court::find()->limit(3)->all();
        $filters = Yii::createObject(MapFilters::className());

        if (Yii::$app->request->getIsPost())
        {
            $filters->load(Yii::$app->request->post());
        }

        $districts = HTMLSelectData::get_list_for_select(new DistrictCity());
        $sport_types = HTMLSelectData::get_list_for_select(new SportType());

        return $this->render('index', [
            'popular' => $popular,
            'filters' => $filters,
            'districts' => $districts,
            'sport_types' => $sport_types
        ]);
//        $filters = $this->get_map_filter_params();



    }

    public function get_map_filter_params()
    {
        $sport_type = Yii::$app->request->getBodyParam('sport_type', 0);
        $district_sity = Yii::$app->request->getBodyParam('district_sity', 0);

        return [
            'sport_type' => $sport_type,
            'district_sity' => $district_sity
        ];
    }
    /**
     * Displays a single Court model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model_form_game_create = Yii::createObject(GameCreateForm::className());
        // $id = 177;
        // получаем id авторизованного пользователя
        if(Yii::$app->user->identity)
            $userAuth = Yii::$app->user->identity->getId();
        else
            $userAuth = 0;

        $query = new Query;
        $query->select('id, address, type_id, name, lat, lon')
            ->from('court')
            ->where(['id' => $id]);
        $court = $query->one();

        $query->select('game.id as id,time, need_ball,COUNT(game_id) as count')
            ->from('game,game_user')
            ->where(['court_id' => $id])
            ->andWhere(['>=','time',date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' + 2 hour'))])
            ->andWhere('game.id = game_user.game_id');
        $games = $query->groupBy('game.id')->orderBy('time')->all();

        // var_dump($games);
        // echo count($games);
        $i = 0;
        foreach ($games as $gameItem) {
            // echo $gameItem['id']."\n";
                $queryPlayer = new Query;
                $queryPlayer->select('user_id')
                    ->from('game_user')
                    ->where(['game_id' => $gameItem['id']]);
                $players = $queryPlayer->all();
                $plus = '+';
                foreach ($players as $player) {
                    // echo $player['user_id']."</br>";
                    if($player['user_id'] == $userAuth)
                        $plus = '-';
                }
                // echo "---</br>";
                $games[$i]['plus'] = $plus;
            $i++;
        }


        $bookmarked = $this->isBookmarked($id) ? true : false;
        $likes_count = count(Yii::createObject(CourtLikes::className())
            ->find()
            ->where(['court_id' => $id])
            ->all())
        ;
        return $this->render('view', [
            'model' => $this->findModel($id),
            'model_form_game_create' => $model_form_game_create,
            'games' => $games,
            'court' => $court,
            'court_json' => json_encode($court),
            'bookmarked' => $bookmarked,
            'likes_count' => $likes_count
        ]);
    }

    public function actionButton_plus()
    {
        $id = Yii::$app->getRequest()->getBodyParam("id");
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
        $gameUser->game_id = $id;
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
                      ->andWhere(['game_id' => $id]);
                $players = $query->all();
                $count = count($players);

        if($symbol == '-')
            $newSymbol = '+';
        else
            $newSymbol = '-';

        if($count == 0)
        {
            $game = Yii::createObject(Game::className());
            $gameDelete = $game->findOne([
                'id' => $id
            ]);
            $gameDelete->delete();
        }

        return $id."|".$newSymbol."|".$count;
    }
    /**
     * Creates a new Court model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Court();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            $district_cities = ArrayHelper::map(DistrictCity::find()
                ->orderBy('name')
                ->all(),
                'id', 'name');
            $types = ArrayHelper::map(CourtType::find()
                ->orderBy('name')
                ->all(),
                'id', 'name');

            return $this->render('create', [
                'model' => $model,
                'district_cities' => $district_cities,
                'types' => $types,
            ]);
        }
    }

    /**
     * Updates an existing Court model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
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
     * Deletes an existing Court model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Court model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Court the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Court::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGet_points()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $query = new Query;
        $query->select('id, lat, lon, name, address, type_id')
              ->from('court');
        $rows = $query->all();
        $i = 0;
        foreach ($rows as $row) {
            $queryPhoto = new Query;
            $queryPhoto->select('photo')
                       ->from('court_photo')
                       ->where(['court_id' => $row['id']])
                       ->andWhere('avatar = 1');
            $rowsPhoto = $queryPhoto->one();

            $queryBookmark = new Query;
            $queryBookmark->select('count(user_id) as countBookmark')
                       ->from('court_bookmark')
                       ->where(['court_id' => $row['id']]);
            $rowsBookmark = $queryBookmark->one();

            if($rowsPhoto['photo']!='')
            {
                $rows[$i]['photo'] = $rowsPhoto['photo'];
            }
            else
            {
                $rows[$i]['photo'] = '0';
            }
            $rows[$i]['bookmark'] = $rowsBookmark['countBookmark'];


            $i++;
        }
        return $rows;
    }

    public function actionBookmark($court_id)
    {
        if (Yii::$app->request->isAjax) {
            //check if court already in current user bookmark's
            $bookmark = $this->isBookmarked($court_id);
            if ($bookmark)
                $bookmark->delete();
            else {
                $bookmark = Yii::createObject(CourtBookmark::className());
                $bookmark->court_id = $court_id;
                $bookmark->user_id = Yii::$app->user->getId();
                $bookmark->save();
            }
        }
    }
    public function actionDistrict_coord($name)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $district = DistrictCity::find()->where(['name' => $name])->one();
            $coord['lat'] = $district['lat'];
            $coord['lng'] = $district['lon'];
            return $coord;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

        function isBookmarked($court_id)
        {

            $user_id = Yii::$app->user->getId();
            $bookmarks = Yii::createObject(CourtBookmark::className());
            $bookmark = $bookmarks->find()->where(['user_id' => $user_id, 'court_id' => $court_id])->one();
            if ($bookmark)
                return $bookmark;
            return false;

        }
}
