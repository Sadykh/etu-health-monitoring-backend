<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'phone',
            'first_name',
            'middle_name',
            'last_name',
            'genderRuName',
            ['attribute' => 'birthday', 'format' => ['date', 'php:d.m.Y']],
            'sms_code_confirm',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'statusRuName',
            //'created_at',
            //'updated_at',
            //'confirmed_at',
            'roleRuName',
            //'firebase_token',
            [
                'label' => 'Действия',
                'content' => function (User $model) {
                    if ($model->isRoleDoctor()) {
                        return Html::a('Сменить на пациента', ['change-role', 'user_id' => $model->id, 'role_id' => User::ROLE_PATIENT], ['class' => 'btn btn-primary']);
                    }
                    return Html::a('Сменить на доктора', ['change-role', 'user_id' => $model->id, 'role_id' => User::ROLE_DOCTOR], ['class' => 'btn btn-primary']);
                },
            ],
        ],
    ]); ?>


</div>
