<?php

use yii\db\Migration;

/**
 * Class m210519_122930_AddPointToUser
 */
class m210519_122930_AddPointToUser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'last_coordinate', 'POINT default null');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'last_coordinate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210519_122930_AddPointToUser cannot be reverted.\n";

        return false;
    }
    */
}
