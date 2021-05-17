<?php

namespace app\modules\admin\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'patient_id', 'doctor_id', 'status_id', 'discharged_at', 'doctor_attempted_at', 'created_at', 'updated_at'], 'integer'],
            [['temperature'], 'number'],
            [['symptoms', 'home_coordinate'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'status_id' => $this->status_id,
            'temperature' => $this->temperature,
            'discharged_at' => $this->discharged_at,
            'doctor_attempted_at' => $this->doctor_attempted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'symptoms', $this->symptoms])
            ->andFilterWhere(['like', 'home_coordinate', $this->home_coordinate]);

        return $dataProvider;
    }
}
