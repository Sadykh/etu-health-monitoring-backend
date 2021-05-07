<?php

namespace app\modules\api;

use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\Response;

/**
 * @OA\Info(
 *   title="Etu health monitoring",
 *   version="1.0.0",
 *   @OA\Contact(
 *   )
 * )
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    private $exceptRoutes = [
        'user/sign-in',
        'user/confirm',
    ];

    public function init()
    {
        parent::init();
        Yii::configure($this, [
            'as contentNegotiator' => [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'as authenticator' => [
                'class' => CompositeAuth::class,
                'except' => $this->exceptRoutes,
                'authMethods' => [
                    HttpBearerAuth::class,
                ],
            ],
            'as access' => [
                'class' => AccessControl::class,
                'except' => $this->exceptRoutes,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);

        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response = $event->sender;
            $errorMessage = isset($response->data['message']) && $response->data['status'] !== 200 ? $response->data['message'] : null;
            $success = $errorMessage ? false : $response->isSuccessful;
            $response->data = [
                'status' => $success,
                'data' => $response->data,
                'errorMessage' => $errorMessage,
            ];
        });
    }

}
