<?php

use yii\db\Migration;

/**
 * Class m210507_203725_AddUserRole
 */
class m210507_203725_AddUserRole extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'role_id', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'role_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210507_203725_AddUserRole cannot be reverted.\n";

        return false;
    }
    */
}
