<?php

namespace app\models\forms;

use app\models\Game;
use Yii;

/**
 * @property integer $day
 */

class GameCreateForm extends Game {

    public $day;
    public $time_digit;

    public function rules()
    {
        return array_merge(parent::rules(),
        [
            ['day', 'required'],
            ['time_digit', 'required'],
            [['day'], 'boolean'],
            ['time_digit', 'time', 'format' => 'H:m', 'skipOnEmpty' => false]
        ]
        );
    }
}
