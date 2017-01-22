<?php

namespace app\migrations;

use app\migrations\Migration;

/**
 * Handles adding approved to table `court_photo`.
 */
class m170122_142718_add_approved_column_to_court_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('court_photo', 'approved', $this->boolean()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('court_photo', 'approved');
    }
}
