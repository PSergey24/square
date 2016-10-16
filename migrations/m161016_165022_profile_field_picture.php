<?php

use yii\db\Migration;

/**
 * Create 'picture' field in table `profile`.
 * Drop fields from profile table: gravatar_email, gravatar_id
 */
class m161016_165022_profile_field_picture extends Migration
{
    public function up()
    {
        $this->addColumn('profile', 'picture', 'string AFTER public_email');
        $this->dropColumn('profile', 'gravatar_email');
        $this->dropColumn('profile', 'gravatar_id');
    }

    public function down()
    {
        $this->dropColumn('profile', 'picture');
        $this->addColumn('profile', 'gravatar_email', 'string AFTER public_email');
        $this->addColumn('profile', 'gravatar_id', 'string AFTER public_email');
    }
}
