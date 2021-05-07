<?php

namespace app\modules\api\controllers;


use app\models\Import;
use yii\rest\Controller;

class ImportController extends Controller
{

    public function actionIndex()
    {
        $data = \Yii::$app->request->getBodyParams();

        $model = new Import();
        $model->hash = $data['hash'];
        $model->url = $data['data']['file_path'];
        $model->status_id = Import::STATUS_NEW;
        $model->save();

        return [
            'code' => 0,
            'message' => 'ok',
            'hash' => $data['hash']
        ];
    }
}