<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string|null $title
 * @property int $order_id
 * @property int $patient_id
 * @property int|null $doctor_id
 * @property int|null $status_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $planned_at
 * @property int|null $done_at
 * @property int|null $removed_at
 *
 * @property User $doctor
 * @property Order $order
 * @property User $patient
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'patient_id'], 'required'],
            [['order_id', 'patient_id', 'doctor_id', 'status_id', 'created_at', 'updated_at', 'done_at', 'removed_at'], 'integer'],
            [['planned_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['doctor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['doctor_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['patient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['patient_id' => 'id']],
        ];
    }

    /**
     * @return string[]
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'order_id' => 'Order ID',
            'patient_id' => 'Patient ID',
            'doctor_id' => 'Doctor ID',
            'status_id' => 'Status ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'planned_at' => 'Planned At',
            'done_at' => 'Done At',
            'removed_at' => 'Removed At',
        ];
    }

    /**
     * Gets query for [[Doctor]].
     *
     * @return \yii\db\ActiveQuery|\app\query\UserQuery
     */
    public function getDoctor()
    {
        return $this->hasOne(User::className(), ['id' => 'doctor_id']);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery|\app\query\OrderQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery|\app\query\UserQuery
     */
    public function getPatient()
    {
        return $this->hasOne(User::className(), ['id' => 'patient_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\query\TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\query\TaskQuery(get_called_class());
    }
}
