<?php

use yii\db\Migration;

/*
 * Create tables 'court', 'game', 'court_sport_type', 'district_city', 'court_type', 'sport_type', 'court_photo'
 * and foreign key's between them
*/

class m161020_161459_create_tables_for_court_and_game extends Migration
{
    public function up()
    {
        $this->createTable('court', [
            'id' => $this->primaryKey(),
            'address' => $this->string()->notNull(),
            'lat' => $this->decimal(10,8)->notNull(),
            'lon' => $this->decimal(11,8)->notNull(),
            'name' => $this->string()->notNull(),
            'built_up_area' => $this->integer(),
            'creator_id' => $this->integer()->notNull(),
            'district_city_id' => $this->integer()->notNull(),
            'type_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'court-creator_id',
            'court',
            'creator_id');

        $this->createIndex(
            'court-district_city_id',
            'court',
            'district_city_id');

        $this->createIndex(
            'court-type_id',
            'court',
            'type_id');

        $this->createTable('game', [
            'id' => $this->primaryKey(),
            'time' => $this->dateTime()->notNull(),
            'need_ball' => $this->boolean()->notNull(),
            'sport_type_id' => $this->integer()->notNull(),
            'court_id' => $this->integer()->notNull(),
            'creator_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'game-sport_type_id',
            'game',
            'sport_type_id');

        $this->createIndex(
            'game-court_id',
            'game',
            'court_id');

        $this->createIndex(
            'game-creator_id',
            'game',
            'creator_id');

        $this->createTable('court_sport_type', [
            'id' => $this->primaryKey(),
            'court_id' => $this->integer()->notNull(),
            'sport_type_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'court_sport_type-court_id',
            'court_sport_type',
            'court_id');

        $this->createIndex(
            'court_sport_type-sport_type_id',
            'court_sport_type',
            'sport_type_id');


        $this->createTable('district_city', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->createTable('court_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->createTable('sport_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->createTable('court_photo', [
            'id' => $this->primaryKey(),
            'court_id' => $this->integer()->notNull(),
            'photo' => $this->string()->notNull(),
            'avatar' => $this->boolean()->notNull(),
        ]);

        $this->createIndex(
            'court_photo-court_id',
            'court_photo',
            'court_id');

        $this->addForeignKey(
            'court-creator_id-user-id',
            'court',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'court-district_city_id-district_city-id',
            'court',
            'district_city_id',
            'district_city',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'court-type_id-court_type-id',
            'court',
            'type_id',
            'court_type',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'game-court_id-court-id',
            'game',
            'court_id',
            'court',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'game-creator_id-user-id',
            'game',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'game-sport_type_id-sport_type-id',
            'game',
            'sport_type_id',
            'sport_type',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'court_sport_type-court_id-court-id',
            'court_sport_type',
            'court_id',
            'court',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'court_sport_type-sport_type_id-sport_type-id',
            'court_sport_type',
            'sport_type_id',
            'sport_type',
            'id',
            'CASCADE',
            'CASCADE'
        );

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

    public function down()
    {
        $this->dropForeignKey(
            'court-creator_id-user-id',
            'court'
        );

        $this->dropForeignKey(
            'court-district_city_id-district_city-id',
            'court'
        );

        $this->dropForeignKey(
            'court-type_id-court_type-id',
            'court'
        );

        $this->dropForeignKey(
            'game-court_id-court-id',
            'game'
        );

        $this->dropForeignKey(
            'game-creator_id-user-id',
            'game'
        );

        $this->dropForeignKey(
            'game-sport_type_id-sport_type-id',
            'game'
        );

        $this->dropForeignKey(
            'court_sport_type-court_id-court-id',
            'court_sport_type'
        );

        $this->dropForeignKey(
            'court_sport_type-sport_type_id-sport_type-id',
            'court_sport_type'
        );
        $this->dropForeignKey(
            'court_photo-court_id-court-id',
            'court_photo'
        );

        $this->dropTable('court');
        $this->dropTable('game');
        $this->dropTable('court_sport_type');
        $this->dropTable('district_city');
        $this->dropTable('court_type');
        $this->dropTable('sport_type');
        $this->dropTable('court_photo');
    }
}
