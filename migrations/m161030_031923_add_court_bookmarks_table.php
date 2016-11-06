<?php

namespace app\migrations;

use app\migrations\Migration;

class m161030_031923_add_court_bookmarks_table extends Migration
{
    public function up()
    {
        $this->createTable('court_bookmark', [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'court_id' => $this->integer()->notNull()
        ], $this->tableOptions);

        $this->createIndex(
            'court_bookmark-user_id',
            'court_bookmark',
            'user_id');

        $this->createIndex(
            'court_bookmark-court_id',
            'court_bookmark',
            'court_id');

        $this->addForeignKey(
            'court_bookmark-user-id',
            'court_bookmark',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'court_bookmark-court_id',
            'court_bookmark',
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
            'court_bookmark-user-id',
            'court_bookmark'
        );

        $this->dropForeignKey(
            'court_bookmark-court_id',
            'court_bookmark'
        );

        $this->dropTable('court_bookmark');
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
