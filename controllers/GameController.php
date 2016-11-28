<?php

namespace app\controllers;

use Yii;
use app\models\Game;
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
        $dataProvider = new ActiveDataProvider([
            'query' => Game::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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

            return self::SUCCESS_CREATE_BOX  . '<p id="warning">Игра успешно создана</p>';

        }
        throw new NotFoundHttpException('The requested page does not exist.');
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
