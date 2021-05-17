<?php

namespace app\modules\api\search;

use app\models\Order;
use app\modules\api\dto\order\OrderShortViewDto;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;

class OrderSearch extends Order
{

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
        ];
    }

    /**
     * @param array $params
     * @param bool  $onlyNew
     * @param bool  $onlyWorking
     * @param       $onlyDischarged
     * @return ActiveDataProvider
     * @throws Exception
     */
    public function search(array $params, bool $onlyNew, bool $onlyWorking, $onlyDischarged): ActiveDataProvider
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

        if ($onlyNew === false) {
            $query->byDoctor(Yii::$app->user->id);
        }
        if ($onlyNew) {
            $query->byStatusNew();
        } else if ($onlyWorking) {
            $query->byStatusTreatment();
        } else if ($onlyDischarged) {
            $query->byStatusDischarged();
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
        foreach ($dataProvider->getModels() as $order) {
            $result[] = new OrderShortViewDto($order);
        }
        $dataProvider->setModels($result);

        return $dataProvider;
    }
}
