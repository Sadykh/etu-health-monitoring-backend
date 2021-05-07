<?php

use yii\db\Migration;

/**
 * Class m210507_144903_CreateOrder
 */
class m210507_144903_CreateOrder extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'doctor_id' => $this->integer()->null(),
            'status_id' => $this->integer()->defaultValue(0),
            'temperature' => $this->float(),
            'symptoms' => $this->text()->notNull(),
            'home_coordinate' => 'POINT default null',
            'discharged_at' => $this->integer(),
            'doctor_attempted_at' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        $this->addForeignKey('order_patient', 'order', 'patient_id', 'user', 'id');
        $this->addForeignKey('order_doctor', 'order', 'doctor_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210507_144903_CreateOrder cannot be reverted.\n";

        return false;
    }
    */
}
