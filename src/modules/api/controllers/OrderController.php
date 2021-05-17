<?php

namespace app\modules\api\controllers;

use app\modules\api\components\ErrorValidationException;
use app\modules\api\forms\order\OrderAcceptForm;
use app\modules\api\forms\order\OrderCreateForm;
use app\modules\api\forms\order\OrderHomeCoordinatesForm;
use app\modules\api\search\OrderSearch;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;

class OrderController extends Controller
{

    /**
     * @return array
     * @OA\Get(
     *      path="/api/order/all",
     *      summary="List all types orders withoit pagination",
     *      tags={"Order"},
     *      description="Method for get list all orders without pagination",
     *      @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="new", type="array", @OA\Items(ref="#/components/schemas/OrderShortViewDto")),
     *              @OA\Property(property="current", type="array", @OA\Items(ref="#/components/schemas/OrderShortViewDto")),
     *              @OA\Property(property="discharged", type="array", @OA\Items(ref="#/components/schemas/OrderShortViewDto")),
     *         )
     *     ),
     * )
     * @throws Exception
     */
    public function actionAll(): array
    {
        return [
            'new' => (new OrderSearch())->search([], true, false, false),
            'current' => (new OrderSearch())->search([], false, true, false),
            'discharged' => (new OrderSearch())->search([], false, false, true),
        ];
    }

    /**
     * @return ActiveDataProvider
     * @OA\Get(
     *      path="/api/order/new",
     *      summary="List new orders",
     *      tags={"Order"},
     *      description="Method for get list new orders without doctor",
     *      @OA\Parameter(
     *          name="offset",
     *          in="query",
     *          @OA\Schema(type="integer", example="20")
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          @OA\Schema(type="integer", example="20")
     *      ),
     *      @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/OrderShortViewDto"),
     *     ),
     * )
     * @throws Exception
     */
    public function actionNew(): ActiveDataProvider
    {
        $search = new OrderSearch();

        return $search->search(Yii::$app->request->queryParams, true, false, false);
    }

    /**
     * @return ActiveDataProvider
     * @OA\Get(
     *      path="/api/order/current",
     *      summary="List current orders (for doctors)",
     *      tags={"Order"},
     *      description="Method for get list current orders (by doctor)",
     *      @OA\Parameter(
     *          name="offset",
     *          in="query",
     *          @OA\Schema(type="integer", example="20")
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          @OA\Schema(type="integer", example="20")
     *      ),
     *      @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/OrderShortViewDto"),
     *     ),
     * )
     * @throws Exception
     */
    public function actionCurrent(): ActiveDataProvider
    {
        $search = new OrderSearch();

        return $search->search(Yii::$app->request->queryParams, false, true, false);
    }

    /**
     * @return ActiveDataProvider
     * @OA\Get(
     *      path="/api/order/discharged",
     *      summary="List discharged orders (for doctors)",
     *      tags={"Order"},
     *      description="Method for get list discharged orders (by doctor)",
     *      @OA\Parameter(
     *          name="offset",
     *          in="query",
     *          @OA\Schema(type="integer", example="20")
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          @OA\Schema(type="integer", example="20")
     *      ),
     *      @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/OrderShortViewDto"),
     *     ),
     * )
     * @throws Exception
     */
    public function actionDischarged(): ActiveDataProvider
    {
        $search = new OrderSearch();

        return $search->search(Yii::$app->request->queryParams, false, false, true);
    }

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

    /**
     * @OA\Post(
     *      path="/api/order/accept",
     *      summary="Accept order by doctor",
     *      tags={"Order"},
     *      description="Method for accept order without doctor",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/OrderAcceptForm"),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="order_id", type="int", example="5"),
     *         )
     *     ),
     * )
     * @throws ErrorValidationException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionAccept(): array
    {
        $data = Yii::$app->request->getBodyParams();
        $model = new OrderAcceptForm();
        $model->load($data);

        $order = $model->save();
        if ($order === null) {
            throw new ErrorValidationException($model->getFirstErrors());
        }

        return [
            'order_id' => $order->id,
        ];
    }

    /**
     * @OA\Post(
     *      path="/api/order/home-coordinates",
     *      summary="Set home coordinates order by doctor",
     *      tags={"Order"},
     *      description="Method for set home coordinates",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/OrderHomeCoordinatesForm"),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="order_id", type="int", example="5"),
     *              @OA\Property(property="point", type="array", @OA\Items(
     *                  @OA\Property(property="latitude", type="int", example="-45.62390335574153"),
     *                  @OA\Property(property="longitude", type="int", example="-3.9551761173743847"),
     *              )),
     *         )
     *     ),
     * )
     * @throws ErrorValidationException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionHomeCoordinates(): array
    {
        $data = Yii::$app->request->getBodyParams();
        $model = new OrderHomeCoordinatesForm();
        $model->load($data);

        $order = $model->save();
        if ($order === null) {
            throw new ErrorValidationException($model->getFirstErrors());
        }

        return [
            'order_id' => $order->id,
            'point' => $order->getNormalHomeCoordinates(),
        ];
    }

}
