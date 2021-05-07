<?php

namespace app\modules\api\forms\order;

use app\models\Order;
use Yii;
use yii\base\Model;

/**
 * Class OrderCreateForm
 * @OA\Schema(
 *     required={"order_id"},
 * )
 * * @package app\modules\api\forms\order
 */
class OrderAcceptForm extends Model
{

    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @var int
     * @OA\Property(
     *     example="100500"
     * )
     */
    public $order_id;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['order_id', 'integer'],
            ['order_id', 'required'],

        ];
    }

    /**
     * @param int $orderId
     * @return Order|null
     */
    public function findOrder(int $orderId): ?Order
    {
        return Order::find()->byStatusNew()->byId($orderId)->one();
    }

    /**
     * @return Order|null
     */
    public function save(): ?Order
    {
        if (!$this->validate()) {
            return null;
        }
        $doctor = Yii::$app->user->identity;
        $model = $this->findOrder($this->order_id);
        if ($model === null) {
            $this->addError('order_id', 'Order not found');
            return null;
        }
        $model->doctor_id = $doctor->id;
        $model->status_id = Order::STATUS_TREATMENT;
        $model->save();

        return $model;
    }
}
