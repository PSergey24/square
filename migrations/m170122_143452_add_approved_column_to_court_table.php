<?php

namespace app\migrations;

use app\migrations\Migration;

/**
 * Handles adding approved to table `court`.
 */
class m170122_143452_add_approved_column_to_court_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('court', 'approved', $this->boolean()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('court', 'approved');
    }
}
