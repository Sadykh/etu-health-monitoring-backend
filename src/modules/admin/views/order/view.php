<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $tasks ActiveDataProvider */

$this->title = 'Заявка №'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$patient = $model->patient;
$doctor = $model->doctor;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">Пациент</div>
                <div class="panel-body">
                    <p>ФИО:
                        <?= "{$patient->last_name} {$patient->first_name} {$patient->middle_name}" ?>
                    </p>
                    <p>Телефон: <?= $patient->phone ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">Врач</div>
                <div class="panel-body">
                    <p>ФИО:
                        <?= $doctor === null ? '' : "{$doctor->last_name} {$doctor->first_name} {$doctor->middle_name}" ?>
                    </p>
                    <p>Телефон: <?= $doctor->phone ?? '' ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-danger">
                <div class="panel-heading">Болезнь</div>
                <div class="panel-body">
                    <p>Температура: <?= $model->temperature ?> C</p>
                    <p>Симптомы: <?= $model->symptoms ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">Состояние заявки</div>
                <div class="panel-body">
                    <p>Текущий статус: <?= $model->getStatusRuName() ?></p>
                    <p>Заявка создана: <?= date('d.m.Y', $model->created_at) ?></p>
                </div>
            </div>
        </div>
    </div>

    <h3>Прием лекарств</h3>
    <?= GridView::widget([
        'dataProvider' => $tasks,
        'columns' => [
            'id',
            'title',
            'planned_at',
        ],
    ]); ?>
</div>
