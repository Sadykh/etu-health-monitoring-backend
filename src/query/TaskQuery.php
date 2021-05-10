<?php

namespace app\query;

/**
 * This is the ActiveQuery class for [[\app\models\Task]].
 * @see \app\models\Task
 */
class TaskQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @return TaskQuery
     */
    public function notDoneNotRemove(): self
    {
        return $this->andWhere(['done_at' => null, 'removed_at' => null]);
    }

    /**
     * @return $this
     */
    public function onlyDone(): self
    {
        return $this->andWhere(['not', ['done_at' => null]]);
    }

    /**
     * @return $this
     */
    public function onlyRemoved(): self
    {
        return $this->andWhere(['not', ['removed_at' => null]]);
    }

    /**
     * @param int $doctorId
     * @return $this
     */
    public function byDoctor(int $doctorId): self
    {
        return $this->andWhere(['doctor_id' => $doctorId]);
    }

    /**
     * @param int $orderId
     * @return $this
     */
    public function byOrder(int $orderId): self
    {
        return $this->andWhere(['order_id' => $orderId]);
    }

    /**
     * @param int $patientId
     * @return $this
     */
    public function byPatient(int $patientId): self
    {
        return $this->andWhere(['patient_id' => $patientId]);
    }

    /**
     * @param int $taskId
     * @return $this
     */
    public function byId(int $taskId): self
    {
        return $this->andWhere(['id' => $taskId]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Task[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Task|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
