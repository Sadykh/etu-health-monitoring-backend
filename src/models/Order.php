<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 * @property int         $id
 * @property int         $patient_id
 * @property int|null    $doctor_id
 * @property int|null    $status_id
 * @property float|null  $temperature
 * @property string      $symptoms
 * @property string|null $home_coordinate
 * @property int|null    $discharged_at
 * @property int|null    $doctor_attempted_at
 * @property int|null    $created_at
 * @property int|null    $updated_at
 * @property User        $doctor
 * @property User        $patient
 */
class Order extends \yii\db\ActiveRecord
{
    public const STATUS_NEW = 0;

    public const STATUS_TREATMENT = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['status_id', 'default', 'value' => self::STATUS_NEW],
            [['patient_id', 'symptoms'], 'required'],
            [['patient_id', 'doctor_id', 'status_id', 'discharged_at', 'doctor_attempted_at', 'created_at', 'updated_at'], 'integer'],
            [['temperature'], 'number'],
            [['symptoms', 'home_coordinate'], 'string'],
            [['doctor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['doctor_id' => 'id']],
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
            'patient_id' => 'Patient ID',
            'doctor_id' => 'Doctor ID',
            'status_id' => 'Status ID',
            'temperature' => 'Temperature',
            'symptoms' => 'Symptoms',
            'home_coordinate' => 'Home Coordinate',
            'discharged_at' => 'Discharged At',
            'doctor_attempted_at' => 'Doctor Attempted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Doctor]].
     * @return \yii\db\ActiveQuery|\app\query\UserQuery
     */
    public function getDoctor()
    {
        return $this->hasOne(User::className(), ['id' => 'doctor_id']);
    }

    /**
     * Gets query for [[Patient]].
     * @return \yii\db\ActiveQuery|\app\query\UserQuery
     */
    public function getPatient()
    {
        return $this->hasOne(User::className(), ['id' => 'patient_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\query\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\query\OrderQuery(get_called_class());
    }
}
