<?php

namespace app\modules\api\forms\task;

use app\models\Order;
use app\models\Task;
use app\models\User;
use app\modules\api\dto\task\TaskShortViewDto;
use DateInterval;
use DatePeriod;
use DateTime;
use Yii;
use yii\base\Model;

/**
 * Class TaskCreateForm
 *
 * @OA\Schema(
 *     required={"order_id", "date_to", "title", "quantity"},
 * )
 * * @package app\modules\api\forms\task
 */
class TaskCreateForm extends Model
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
     *     example="2021-05-25"
     * )
     *
     */
    public $date_to;

    /**
     * @var string
     * @OA\Property(
     *     example="Арбидол"
     * )
     */
    public $title;

    /**
     * @var int
     * @OA\Property(
     *     example="Количество"
     * )
     *
     */
    public $quantity;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['title', 'string'],
            [['order_id', 'quantity'], 'integer'],
            ['date_to', 'date', 'format' => 'Y-m-d'],
        ];
    }

    /**
     * @param int  $orderId
     * @param User $doctor
     * @return Order|null
     */
    public function findOrder(int $orderId, User $doctor): ?Order
    {
        return Order::find()->byId($orderId)->byStatusWorking()->byDoctor($doctor->id)->one();
    }

    public function create(): array
    {
        if (!$this->validate()) {
            return [];
        }
        $order = $this->findOrder($this->order_id, Yii::$app->user->identity);

        if ($order === null) {
            $this->addError('order_id', 'Order not found');

            return [];
        }

        $begin = new DateTime();
        $end = (new DateTime($this->date_to))->modify("+1 day");

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $result = [];
        foreach ($period as $dt) {
            for ($i = 1; $i <= $this->quantity; $i++) {
                $task = new Task();
                $task->order_id = $order->id;
                $task->doctor_id = $order->doctor_id;
                $task->patient_id = $order->patient_id;
                $task->title = $this->title.", {$i} прием";
                $task->planned_at = $dt->format("Y-m-d");
                $task->save();
                $result[] = new TaskShortViewDto($task);
            }
        }

        return $result;
    }
}
