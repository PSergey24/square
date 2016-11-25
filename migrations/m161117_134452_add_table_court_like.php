<?php

namespace app\migrations;

use app\migrations\Migration;

class m161117_134452_add_table_court_like extends Migration
{
    public function up()
    {
        $this->createTable('court_likes', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'court_id' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex(
            'court_likes-user_id',
            'court_likes',
            'user_id');

        $this->createIndex(
            'court_likes-court_id',
            'court_likes',
            'court_id');

        $this->addForeignKey(
            'court_likes-user_id-user-id',
            'court_likes',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'court_likes-court_id-court-id',
            'court_likes',
            'court_id',
            'court',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'court_likes-user_id-user-id',
            'court_likes'
        );

        $this->dropForeignKey(
            'court_likes-court_id-court-id',
            'court_likes'
        );

        $this->dropTable('court_likes');
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
