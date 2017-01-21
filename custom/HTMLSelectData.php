<?php

namespace app\custom;

use yii\base\Model;

class HTMLSelectData {

    public static function get_list_for_select(Model $model) {

        $model_objects = $model->find()->all();

        if ($model_objects != null) {

            foreach ($model_objects as $object) {
                $list[$object->id] = $object->name;
            }

            return $list;
        }
    }

}