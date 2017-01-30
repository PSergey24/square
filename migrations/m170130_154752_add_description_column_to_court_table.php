<?php

namespace app\migrations;

use app\migrations\Migration;

/**
 * Handles adding approved to table `court`.
 */
class m170130_154752_add_description_column_to_court_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('court', 'description', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('court', 'description');
    }
}
