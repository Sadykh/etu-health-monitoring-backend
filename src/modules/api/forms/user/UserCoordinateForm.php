<?php

namespace app\modules\api\forms\user;

use app\models\Order;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\db\Expression;

/**
 * Class OrderHomeCoordinatesForm
 * @OA\Schema(
 *     required={"latitude", "longitude"},
 * )
 * * @package app\modules\api\forms\order
 */
class UserCoordinateForm extends Model
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
     *     example=59.971920
     * )
     */
    public $latitude;

    /**
     * @var int
     * @OA\Property(
     *     example=30.324516
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
            [['latitude', 'longitude'], 'required'],

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
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $user = Yii::$app->user->identity;

        $params = [
            ':latitude' => $this->latitude,
            ':longitude' => $this->longitude,
            ':user_id' => $user->id,
        ];
        $sql = new Expression("UPDATE `user` SET `last_coordinate`=POINT(:latitude, :longitude) WHERE `id` = :user_id");
        Yii::$app->db->createCommand($sql, $params)->execute();

        return true;
    }
}
