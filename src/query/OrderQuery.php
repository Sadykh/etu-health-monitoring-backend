<?php

namespace app\query;

use app\models\Order;

/**
 * This is the ActiveQuery class for [[\app\models\Order]].
 * @see \app\models\Order
 */
class OrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @param int $orderId
     * @return $this
     */
    public function byId(int $orderId): self
    {
        return $this->andWhere(['id' => $orderId]);
    }

    /**
     * @param int $statusId
     * @return $this
     */
    public function byStatus(int $statusId): self
    {
        return $this->andWhere(['status_id' => $statusId]);
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
     * @param int $doctorId
     * @return $this
     */
    public function byDoctor(int $doctorId): self
    {
        return $this->andWhere(['doctor_id' => $doctorId]);
    }

    /**
     * @return $this
     */
    public function byStatusNew(): self
    {
        return $this->byStatus(Order::STATUS_NEW);
    }

    /**
     * @return $this
     */
    public function byStatusWorking(): self
    {
        return $this->andFilterWhere(['or',
            ['=', 'status_id', Order::STATUS_NEW],
            ['=', 'status_id', Order::STATUS_TREATMENT]]);
    }

    /**
     * @return $this
     */
    public function byStatusTreatment(): self
    {
        return $this->andWhere(['status_id' => Order::STATUS_TREATMENT]);
    }

    /**
     * @return $this
     */
    public function byStatusDischarged(): self
    {
        return $this->byStatus(Order::STATUS_DISCHARGED);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
