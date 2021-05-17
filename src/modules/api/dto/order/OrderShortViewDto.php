<?php

namespace app\modules\api\dto\order;

use app\models\Order;
use DateTime;
use DateTimeZone;
use Exception;

/**
 * Class OrderShortViewDto
 * @OA\Schema()
 * * @package app\modules\api\dto\order
 */
class OrderShortViewDto
{

    /**
     * @var int
     * @OA\Property(
     *     example="100500"
     * )
     */
    public $order_id;

    /**
     * @var string|null
     * @OA\Property(
     *     example="Василий"
     * )
     */
    public $first_name;

    /**
     * @var string|null
     * @OA\Property(
     *     example="Пупкин"
     * )
     *
     */
    public $last_name;

    /**
     * @var string|null
     * @OA\Property(
     *     example="Петрович"
     * )
     *
     */
    public $middle_name;

    /**
     * @var float|null
     * @OA\Property(
     *     example="36.6"
     * )
     *
     */
    public $temperature;

    /**
     * @var string
     * @OA\Property(
     *     example="Болит голова, живот, нога, глаза"
     * )
     *
     */
    public $symptoms;

    /**
     * @var string
     * @OA\Property(
     *     example="new, treatment, discharged"
     * )
     *
     */
    public $status;

    /**
     * @var int|null
     * @OA\Property(
     *     example="100500"
     * )
     *
     */
    public $created_at;

    /**
     * @var int|null
     * @OA\Property(
     *     example="25"
     * )
     *
     */
    public $age;

    /**
     * @throws Exception
     */
    public function calculateAge(?string $birthday): ?int
    {
        $tz = new DateTimeZone('Europe/Moscow');
        $date = DateTime::createFromFormat('Y-m-d', $birthday, $tz);
        if ($date === false) {
            return null;
        }

        return $date->diff(new DateTime('now', $tz))->y;
    }

    /**
     * OrderShortViewDto constructor.
     * @param Order $order
     * @throws Exception
     */
    public function __construct(Order $order)
    {
        $patient = $order->patient;
        $this->order_id = $order->id;
        $this->first_name = $patient->first_name;
        $this->last_name = $patient->last_name;
        $this->middle_name = $patient->middle_name;
        $this->temperature = $order->temperature;
        $this->symptoms = $order->symptoms;
        $this->created_at = $order->created_at;
        $this->status = $order->getStatusName();
        $this->age = $this->calculateAge($patient->birthday);
    }
}
