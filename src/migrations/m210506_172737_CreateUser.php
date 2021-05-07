<?php

use yii\db\Migration;

/**
 * Class m210506_172737_CreateUser
 */
class m210506_172737_CreateUser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'phone' => $this->string()->notNull()->unique(),
            'first_name' => $this->string()->null(),
            'middle_name' => $this->string()->null(),
            'last_name' => $this->string()->null(),
            'gender' => $this->string()->null(),
            'birthday' => $this->date()->null(),
            'sms_code_confirm' => $this->string()->null(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'status_id' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'confirmed_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210506_172737_CreateUser cannot be reverted.\n";

        return false;
    }
    */
}
