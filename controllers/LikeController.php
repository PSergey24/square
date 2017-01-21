<?php

namespace app\controllers;

use Yii;
use app\models\CourtLikes;
use yii\rest\ActiveController;

class LikeController extends ActiveController
{
    public $modelClass = "app\models\CourtLikes";
    public $court_like;

    public function init()
    {
        parent::init();
        $this->court_like = Yii::createObject(CourtLikes::className());
        $this->court_like->court_id = Yii::$app->getRequest()->getBodyParam("court_id");
        $this->court_like->user_id = Yii::$app->getRequest()->getBodyParam("user_id");
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['view']);
        unset($actions['index']);
        unset($actions['delete']);

        return $actions;
    }

    protected function verbs()
    {
        return [
            'index'=> ['GET'],
            'create' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex($id)
    {
        return count($this->court_like->find()->where(['court_id' => $id])->All());
    }

    public function actionCreate()
    {
        //check if like of specific user already exist
        if ($this->actionHasLike())
            return 'Like already exist';
        else
            return $this->court_like->save();
    }

    public function actionHasLike()
    {
        //check if like of specific user already exist
        if (
            $this->court_like->find()
                ->where(
                    [
                        'court_id' => $this->court_like->court_id,
                        'user_id' => $this->court_like->user_id
                    ])
                ->exists()
        )
            return true;
        else
            return false;
    }

    public function actionDelete() {

        $like = $this->court_like->findOne([
            'court_id' => $this->court_like->court_id,
            'user_id' => $this->court_like->user_id
        ]);
        return $like->delete() ? true : false;
    }
}
