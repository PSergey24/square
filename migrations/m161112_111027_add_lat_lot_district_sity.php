<?php

namespace app\migrations;

use yii\db\Migration;


class m161112_111027_add_lat_lot_district_sity extends Migration
{
    public function up()
    {
        $this->addColumn('district_city', 'lat', 'decimal(10,8) NOT NULL AFTER name');
        $this->addColumn('district_city', 'lon', 'decimal(11,8) NOT NULL AFTER lat');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('district_city', 'lat');
        $this->dropColumn('district_city', 'lon');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
