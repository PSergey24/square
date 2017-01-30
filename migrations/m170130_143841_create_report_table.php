<?php

namespace app\migrations;

use app\migrations\Migration;

/**
 * Handles the creation of table `report`.
 * Has foreign keys to the tables:
 *
 * - `court`
 * - `user`
 */
class m170130_143841_create_report_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('report', [
            'id' => $this->primaryKey(),
            'court_id' => $this->integer(),
            'title' => $this->string(),
            'description' => $this->string(),
            'user_id' => $this->integer(),
        ]);

        // creates index for column `court_id`
        $this->createIndex(
            'idx-report-court_id',
            'report',
            'court_id'
        );

        // add foreign key for table `court`
        $this->addForeignKey(
            'fk-report-court_id',
            'report',
            'court_id',
            'court',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-report-user_id',
            'report',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-report-user_id',
            'report',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `court`
        $this->dropForeignKey(
            'fk-report-court_id',
            'report'
        );

        // drops index for column `court_id`
        $this->dropIndex(
            'idx-report-court_id',
            'report'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-report-user_id',
            'report'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-report-user_id',
            'report'
        );

        $this->dropTable('report');
    }
}
