<?php

namespace app\migrations;

use app\migrations\Migration;

/**
 * Handles the creation of table `court_photo`.
 */
class m170121_150014_create_court_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('court_photo', [
            'id' => $this->primaryKey(),
            'court_id' => $this->integer()->notNull(),
            'photo' => $this->varchar()->notNull(),
            'avatar' => $this->tinyint()->notNull(),
            'approved' => $this->tinyint()->notNull(),
        ], $this->tableOptions);
    
        $this->createIndex(
            'court_photo-court_id',
            'court_photo',
            'court_id');

        $this->addForeignKey(
            'court_photo-court_id-court-id',
            'court_photo',
            'court_id',
            'court',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'court_photo-court_id-court-id',
            'court_photo'
        );

        $this->dropTable('court_photo');
    }
}
