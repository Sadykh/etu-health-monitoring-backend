<?php

namespace app\modules\api\search;

use app\models\Task;
use app\modules\api\dto\task\TaskShortViewDto;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;

class TaskSearch extends Task
{

    public $order_id;

    public $offset;

    public $limit;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['offset', 'default', 'value' => 0],
            ['limit', 'default', 'value' => 20],
            ['limit', 'integer', 'max' => 20],
            ['offset', 'integer'],
            ['order_id', 'integer'],
            ['order_id', 'required'],
        ];
    }

    /**
     * @param array $params
     * @param bool  $onlyActive
     * @param bool  $onlyDone
     * @param bool  $onlyRemoved
     * @return ActiveDataProvider
     * @throws Exception
     */
    public function search(array $params, bool $onlyActive, bool $onlyDone, bool $onlyRemoved): ActiveDataProvider
    {
        $query = self::find();
        $this->load($params, '');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        if (!$this->validate()) {
            return $this->getResultDataProvider($dataProvider);
        }

        $query->limit($this->limit);
        $query->offset($this->offset);
        $query->byOrder($this->order_id);

        if (Yii::$app->user->identity->isRoleDoctor()) {
            $query->byDoctor(Yii::$app->user->id);
        } else {
            $query->byPatient(Yii::$app->user->id);
        }
        if ($onlyActive) {
            $query->notDoneNotRemove();
        } else if ($onlyDone) {
            $query->onlyDone();
        } else if ($onlyRemoved) {
            $query->byDoctor(Yii::$app->user->id);
            $query->onlyRemoved();
        }

        return $this->getResultDataProvider($dataProvider);
    }

    /**
     * @param ActiveDataProvider $dataProvider
     * @return ActiveDataProvider
     * @throws Exception
     */
    public function getResultDataProvider(ActiveDataProvider $dataProvider): ActiveDataProvider
    {
        $result = [];
        foreach ($dataProvider->getModels() as $task) {
            $result[] = new TaskShortViewDto($task);
        }
        $dataProvider->setModels($result);

        return $dataProvider;
    }
}
