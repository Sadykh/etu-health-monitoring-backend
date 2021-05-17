<?php

namespace app\modules\api\forms\order;

use app\models\Order;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\db\Expression;

/**
 * Class OrderHomeCoordinatesForm
 * @OA\Schema(
 *     required={"order_id", "latitude", "longitude"},
 * )
 * * @package app\modules\api\forms\order
 */
class OrderHomeCoordinatesForm extends Model
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
     * @var int
     * @OA\Property(
     *     example="59.971920"
     * )
     */
    public $latitude;

    /**
     * @var int
     * @OA\Property(
     *     example="30.324516"
     * )
     */
    public $longitude;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['latitude', 'number'],
            ['longitude', 'number'],
            ['order_id', 'integer'],
            [['order_id', 'latitude', 'longitude'], 'required'],

        ];
    }

    /**
     * @param int $orderId
     * @return Order|null
     */
    public function findOrder(int $orderId): ?Order
    {
        return Order::find()->byStatusWorking()->byId($orderId)->one();
    }

    /**
     * @return Order|null
     * @throws Exception
     */
    public function save(): ?Order
    {
        if (!$this->validate()) {
            return null;
        }
        $doctor = Yii::$app->user->identity;
        $model = $this->findOrder($this->order_id);
        if ($model === null || $model->doctor_id !== $doctor->id) {
            $this->addError('order_id', 'Order not found');

            return null;
        }

        $params = [
            ':latitude' => $this->latitude,
            ':longitude' => $this->longitude,
            ':order_id' => $this->order_id,
        ];
        $sql = new Expression("UPDATE `order` SET `home_coordinate`=POINT(:latitude, :longitude) WHERE `id` = :order_id");
        Yii::$app->db->createCommand($sql, $params)->execute();

        return $this->findOrder($this->order_id);
    }
}
