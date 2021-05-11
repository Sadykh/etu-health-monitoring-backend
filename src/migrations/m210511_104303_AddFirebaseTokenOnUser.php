<?php

use yii\db\Migration;

/**
 * Class m210511_104303_AddFirebaseTokenOnUser
 */
class m210511_104303_AddFirebaseTokenOnUser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'firebase_token', $this->string()->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'firebase_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210511_104303_AddFirebaseTokenOnUser cannot be reverted.\n";

        return false;
    }
    */
}
