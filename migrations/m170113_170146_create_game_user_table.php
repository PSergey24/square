<?php

namespace app\migrations;

use app\migrations\Migration;

/**
 * Handles the creation of table `game_user`.
 */
class m170113_170146_create_game_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('game_user', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $this->tableOptions);


        $this->createIndex(
            'game_user-game_id',
            'game_user',
            'game_id');

        $this->createIndex(
            'game_user-user_id',
            'game_user',
            'user_id');

        $this->addForeignKey(
            'game_user-game_id-game-id',
            'game_user',
            'game_id',
            'game',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'game_user-user_id-profile-user_id',
            'game_user',
            'user_id',
            'profile',
            'user_id',
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
            'game_user-game_id-game-id',
            'game_user'
        );

        $this->dropForeignKey(
            'game_user-user_id-profile-user_id',
            'game_user'
        );

        $this->dropTable('game_user');
    }
}
