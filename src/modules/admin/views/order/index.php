<?php

use app\models\Order;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'patient_id',
                'content' => static function (Order $model) {
                    return $model->patient->middle_name.' '.$model->patient->first_name;
                },
            ],
            [
                'attribute' => 'doctor_id',
                'content' => static function (Order $model) {
                    if ($model->doctor === null) {
                        return 'не назначен';
                    }

                    return $model->doctor->middle_name.' '.$model->doctor->first_name;
                },
            ],
            [
                'attribute' => 'statusRuName',
                'content' => static function (Order $model) {
                    $labelClass = 'label-warning';
                    if ($model->isStatusNew()) {
                        $labelClass = 'label-danger';
                    } else if ($model->isStatusDischarged()) {
                        $labelClass = 'label-success';
                    }

                    return '<span class="label '.$labelClass.'">'.$model->getStatusRuName().'</span>';
                },
            ],
            'temperature',
            'symptoms:ntext',
            //'home_coordinate',
            //'discharged_at',
            //'doctor_attempted_at',
            //'created_at',
            //'updated_at',
            ['label' => 'Действия',
                'content' => static function (Order $model) {
                    $result = '<div class="btn-group">';
                    $result .= Html::a('Посмотреть', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    if ($model->isStatusNew()) {
                        $result .= Html::a('Удалить', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger']);
                    }
                    $result .= '</div>';

                    return $result;
                },
            ],
        ],
    ]); ?>


</div>
