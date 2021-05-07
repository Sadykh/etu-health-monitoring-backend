<?php

namespace app\modules\api\controllers;

use app\modules\api\components\ErrorValidationException;
use app\modules\api\forms\order\OrderCreateForm;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Controller;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/order/create",
     *      summary="Create order",
     *      tags={"Order"},
     *      description="Method for create order of treatment",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/OrderCreateForm"),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="temperature", type="float", example="37.7"),
     *              @OA\Property(property="symptoms", type="text", example="Болит голова, живот, нога, глаза"),
     *         )
     *     ),
     * )
     * @throws ErrorValidationException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionCreate(): array
    {
        $data = Yii::$app->request->getBodyParams();

        $model = new OrderCreateForm();
        $model->load($data);

        $order = $model->save();
        if ($order === null) {
            throw new ErrorValidationException($model->getFirstErrors());
        }

        return [
            'temperature' => $order->temperature,
            'symptoms' => $order->symptoms,
        ];
    }

}
