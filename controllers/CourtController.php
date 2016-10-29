<?php

namespace app\controllers;

use app\models\CourtType;
use Yii;
use app\models\Court;
use app\models\CourtSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\DistrictCity;
use yii\helpers\ArrayHelper;
use app\common\MapCreate;
use yii\db\Query;
use yii\web\Response;

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
        return $this->render('index', [
            'popular' => $popular
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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
            $map = Yii::createObject(MapCreate::class,[1000, 300, MapCreate::SPB_city_name])->create();
            
//            $map_type_control_options = Yii::createObject(MapTypeControlOptions::className());
//            $map_type_control_options->setStyle(MapTypeControlStyle::DROPDOWN_MENU);
//            $map_type_control_options->setPosition(ControlPosition::TOP_LEFT);
//            var_dump($map_type_control_options);

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
                'map' => $map,
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

    public function actionGet_points() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $query = new Query;
        $query->select('id, lat, lon, name, address, type_id')
            ->from('court');
        $rows = $query->all();


        return $rows;
    }
}
