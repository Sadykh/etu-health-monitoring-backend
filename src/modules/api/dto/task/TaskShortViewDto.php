<?php

namespace app\modules\api\dto\task;

use app\models\Task;

/**
 * Class TaskShortViewDto
 * @OA\Schema()
 * @package app\modules\api\dto\task
 */
class TaskShortViewDto
{

    /**
     * @var int
     * @OA\Property(
     *     example="100500"
     * )
     */
    public $id;

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
     *     example="Доктор Мом"
     * )
     */
    public $title;

    /**
     * @var string|null
     * @OA\Property(
     *     example="2021-05-25"
     * )
     */
    public $planned_at;

    /**
     * @var int|null
     * @OA\Property(
     *     example="2021-05-25"
     * )
     */
    public $done_at;

    /**
     * @var int|null
     * @OA\Property(
     *     example="2021-05-25"
     * )
     */
    public $removed_at;

    /**
     * TaskShortViewDto constructor.
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->id = $task->id;
        $this->order_id = $task->order_id;
        $this->title = $task->title;
        $this->planned_at = $task->planned_at;
        $this->done_at = $task->done_at;
        $this->removed_at = $task->removed_at;
    }

}
