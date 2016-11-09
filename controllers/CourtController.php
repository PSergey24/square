<?php

namespace app\controllers;

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
use app\models\DistrictCity;
use app\models\forms\GameCreateForm;

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
        $query = new Query;
        $query->select('*')
            ->from('court')
            ->limit(3);
        $popular = $query->all();
        $sport_type = 0;
        if (isset(Yii::$app->getRequest()->queryParams['sport_type']))
            $sport_type = Yii::$app->getRequest()->queryParams['sport_type'];

        return $this->render('index', [
            'popular' => $popular,
            'sport_type' => $sport_type
        ]);
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
        $query = new Query;
        $query->select('id, address, type_id, name, lat, lon')
            ->from('court')
            ->where(['id' => $id]);
        $court = $query->one();
        
        $query->select('time, need_ball')
            ->from('game')
            ->where(['court_id' => $id]);
        $games = $query->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'model_form_game_create' => $model_form_game_create,
            'games' => $games,
            'court' => $court,
            'court_json' => json_encode($court)
        ]);
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


        return $rows;
    }

    public function actionBookmark($court_id)
    {
        if (Yii::$app->request->isAjax) {
            $bookmark = Yii::createObject(CourtBookmark::className());
            $bookmark->court_id = $court_id;
            $bookmark->user_id = Yii::$app->user->getId();
            $bookmark->save();

        }
    }
}
