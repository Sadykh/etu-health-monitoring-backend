<?php

namespace app\modules\api\forms\order;

use app\models\Order;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Class OrderCreateForm
 * @OA\Schema(
 *     required={"temperature", "symptoms"},
 * )
 * * @package app\modules\api\forms\order
 */
class OrderCreateForm extends Model
{

    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @var float
     * @OA\Property(
     *     example="37.6"
     * )
     */
    public $temperature;

    /**
     * @var string
     * @OA\Property(
     *     example="Болит голова, живот, нога, глаза"
     * )
     */
    public $symptoms;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['temperature', 'number'],
            ['symptoms', 'string'],
            [['temperature', 'symptoms'], 'required'],
        ];
    }

    /**
     * @param User $user
     * @return Order|null
     */
    public function findOrder(User $user): ?Order
    {
        return Order::find()->byStatusWorking()->byPatient($user->id)->one();
    }

    /**
     * @return Order|null
     */
    public function save(): ?Order
    {
        if (!$this->validate()) {
            return null;
        }
        $patient = Yii::$app->user->identity;
        if ($this->findOrder($patient) !== null) {
            $this->addError('order_id', 'Order exist');
            return null;
        }
        $model = new Order();
        $model->patient_id = $patient->id;
        $model->temperature = $this->temperature;
        $model->symptoms = $this->symptoms;
        $model->save();

        return $model;
    }
}
