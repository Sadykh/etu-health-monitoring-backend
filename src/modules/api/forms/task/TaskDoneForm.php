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
 * Class TaskDoneForm
 * @OA\Schema(
 *     required={"task_id"},
 * )
 * * @package app\modules\api\forms\task
 */
class TaskDoneForm extends Model
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
    public $task_id;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['task_id'], 'integer'],
        ];
    }

    /**
     * @param int  $taskId
     * @param User $doctor
     * @return Order|null
     */
    public function findTask(int $taskId, User $patient): ?Task
    {
        return Task::find()->byId($taskId)->notDoneNotRemove()->byPatient($patient->id)->one();
    }

    /**
     * @return bool
     */
    public function done(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $task = $this->findTask($this->task_id, Yii::$app->user->identity);

        if ($task === null) {
            $this->addError('task_id', 'Task not found');

            return false;
        }

        $task->done_at = time();
        $task->save();

        return true;
    }
}
