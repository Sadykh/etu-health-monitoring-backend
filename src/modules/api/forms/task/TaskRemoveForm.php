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
 * @OA\Schema(
 *     required={"task_id"},
 * )
 * * @package app\modules\api\forms\task
 */
class TaskRemoveForm extends Model
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
    public function findTask(int $taskId, User $doctor): ?Task
    {
        return Task::find()->byId($taskId)->notDoneNotRemove()->byDoctor($doctor->id)->one();
    }

    public function remove(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $task = $this->findTask($this->task_id, Yii::$app->user);

        if ($task === null) {
            $this->addError('task_id', 'Task not found');

            return false;
        }

        $task->removed_at = time();
        $task->save();

        return true;
    }
}
