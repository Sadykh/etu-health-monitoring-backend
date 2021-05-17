<?php

namespace app\modules\admin\search;

use app\models\Task;
use Exception;
use yii\data\ActiveDataProvider;

class TaskSearch extends Task
{

    /**
     * @param array $params
     * @param int   $orderId
     * @return ActiveDataProvider
     * @throws Exception
     */
    public function search(array $params, int $orderId): ActiveDataProvider
    {
        $query = self::find();
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'planned_at' => SORT_ASC,
                    'id' => SORT_ASC,
                ]],

        ]);

        $query->byOrder($orderId);

        return $dataProvider;
    }
}
