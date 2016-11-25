<?php

namespace app\controllers;

use Yii;
use app\models\CourtLikes;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class LikeController extends Controller
{
    /**
     * Displays a single Court model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionIndex($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $court_likes = Yii::createObject(CourtLikes::className());
            return count($court_likes->find()->where(['court_id' => $id])->All());
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAdd()
    {
//        if (Yii::$app->request->isAjax) {
            $court_id = Yii::$app->getRequest()->getBodyParam("court_id");
            $user_id = Yii::$app->getRequest()->getBodyParam("user_id");
            $court_like = Yii::createObject(CourtLikes::className());
            $court_like->user_id = $user_id;
            $court_like->court_id= $court_id;

            return $court_like->save();
//        }else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
    }

}
