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

    public const STATUS_DISCHARGED = 2;

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
            [['patient_id', 'doctor_id', 'status_id', 'discharged_at',
                'doctor_attempted_at', 'created_at', 'updated_at'], 'integer'],
            [['temperature'], 'number'],
            [['symptoms', 'home_coordinate'], 'string'],
            [['doctor_id'], 'exist', 'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['doctor_id' => 'id']],
            [['patient_id'], 'exist', 'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['patient_id' => 'id']],
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
            'patient_id' => 'Пациент',
            'doctor_id' => 'Врач',
            'status_id' => 'Статус',
            'statusRuName' => 'Статус',
            'temperature' => 'Температура, С',
            'symptoms' => 'Симптомы',
            'home_coordinate' => 'Home Coordinate',
            'discharged_at' => 'Выписан',
            'doctor_attempted_at' => 'Доктор назначен',
            'created_at' => 'Создана',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Doctor]].
     * @return \yii\db\ActiveQuery|\app\query\UserQuery
     */
    public function getDoctor()
    {
        return $this
            ->hasOne(User::className(), ['id' => 'doctor_id']);
    }

    /**
     * Gets query for [[Patient]].
     * @return \yii\db\ActiveQuery|\app\query\UserQuery
     */
    public function getPatient()
    {
        return $this
            ->hasOne(User::className(), ['id' => 'patient_id']);
    }

    /**
     * @return \app\query\OrderQuery
     */
    public static function find()
    {
        return new \app\query\OrderQuery(get_called_class());
    }

    /**
     * @return string[]
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_NEW => 'new',
            self::STATUS_TREATMENT => 'treatment',
            self::STATUS_DISCHARGED => 'discharged',
        ];
    }

    /**
     * @return string
     */
    public function getStatusName(): string
    {
        $list = self::getStatusList();

        return $list[$this->status_id];
    }

    /**
     * @return string[]
     */
    public static function getStatusRuList(): array
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_TREATMENT => 'На лечении',
            self::STATUS_DISCHARGED => 'Выписан',
        ];
    }

    /**
     * @return string
     */
    public function getStatusRuName(): string
    {
        $list = self::getStatusRuList();

        return $list[$this->status_id];
    }

    /**
     * @return bool
     */
    public function isStatusNew(): bool
    {
        return $this->status_id === self::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isStatusDischarged(): bool
    {
        return $this->status_id === self::STATUS_DISCHARGED;
    }

    public function getNormalHomeCoordinates(): ?array
    {
        if ($this->home_coordinate === null) {
            return null;
        }

        $coordinates = unpack(
            'x/x/x/x/corder/Ltype/dlat/dlon',
            $this->home_coordinate
        );

        return [
            'latitude' => $coordinates['lat'],
            'longitude' => $coordinates['lon'],
        ];
    }
}
