<?php

use yii\db\Migration;

/**
 * Add default value for 'email' field in table `user`.
 */
class m161012_175920_add_default_null_email extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('{{%user}}', 'email', 'string DEFAULT NULL');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('{{%user}}', 'email', 'string DEFAULT None');
    }
}
