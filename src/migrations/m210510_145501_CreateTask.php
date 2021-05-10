<?php

use yii\db\Migration;

/**
 * Class m210510_145501_CreateTask
 */
class m210510_145501_CreateTask extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'order_id' => $this->integer()->notNull(),
            'patient_id' => $this->integer()->notNull(),
            'doctor_id' => $this->integer()->null(),
            'status_id' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'planned_at' => $this->date(),
            'done_at' => $this->integer(),
            'removed_at' => $this->integer(),
        ]);
        $this->addForeignKey('task_patient', 'task', 'patient_id', 'user', 'id');
        $this->addForeignKey('task_doctor', 'task', 'doctor_id', 'user', 'id');
        $this->addForeignKey('task_order', 'task', 'order_id', 'order', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('task');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210510_145501_CreateTask cannot be reverted.\n";

        return false;
    }
    */
}
