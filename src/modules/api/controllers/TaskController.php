<?php

namespace app\modules\api\controllers;

use app\modules\api\components\ErrorValidationException;
use app\modules\api\forms\task\TaskCreateForm;
use app\modules\api\forms\task\TaskDoneForm;
use app\modules\api\forms\task\TaskRemoveForm;
use app\modules\api\search\TaskSearch;
use Exception;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;

class TaskController extends Controller
{

    /**
     * @return array
     * @OA\Get(
     *      path="/api/task/all",
     *      summary="List all task ",
     *      tags={"Task"},
     *      description="Method for get list all task without pagination",
     *      @OA\Parameter(
     *          name="order_id",
     *          in="query",
     *          @OA\Schema(type="integer", example="100500")
     *      ),
     *      @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="active", type="array", @OA\Items(ref="#/components/schemas/TaskShortViewDto")),
     *              @OA\Property(property="done", type="array", @OA\Items(ref="#/components/schemas/TaskShortViewDto")),
     *              @OA\Property(property="removed", type="array", @OA\Items(ref="#/components/schemas/TaskShortViewDto")),
     *         )
     *     ),
     * )
     * @throws Exception
     */
    public function actionAll()
    {
        $params = Yii::$app->request->queryParams;

        return [
            'active' => (new TaskSearch())->search($params, true, false, false),
            'done' => (new TaskSearch())->search($params, false, true, false),
            'removed' => (new TaskSearch())->search($params, false, false, true),
        ];
    }

    /**
     * @return ActiveDataProvider
     * @OA\Get(
     *      path="/api/task/active",
     *      summary="List active task",
     *      tags={"Task"},
     *      description="Method for get list active task ",
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
     *      @OA\Parameter(
     *          name="order_id",
     *          in="query",
     *          @OA\Schema(type="integer", example="100500")
     *      ),
     *      @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/TaskShortViewDto"),
     *     ),
     * )
     * @throws Exception
     */
    public function actionActive()
    {
        $params = Yii::$app->request->queryParams;
        return (new TaskSearch())->search($params, true, false, false);
    }

    /**
     * @return ActiveDataProvider
     * @OA\Get(
     *      path="/api/task/done",
     *      summary="List done task",
     *      tags={"Task"},
     *      description="Method for get list done task",
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
     *      @OA\Parameter(
     *          name="order_id",
     *          in="query",
     *          @OA\Schema(type="integer", example="100500")
     *      ),
     *      @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/TaskShortViewDto"),
     *     ),
     * )
     * @throws Exception
     */
    public function actionDone()
    {
        $params = Yii::$app->request->queryParams;
        return (new TaskSearch())->search($params, false, true, false);
    }

    /**
     * @return ActiveDataProvider
     * @OA\Get(
     *      path="/api/task/removed",
     *      summary="List active task",
     *      tags={"Task"},
     *      description="Method for get list removed task ",
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
     *      @OA\Parameter(
     *          name="order_id",
     *          in="query",
     *          @OA\Schema(type="integer", example="100500")
     *      ),
     *      @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/TaskShortViewDto"),
     *     ),
     * )
     * @throws Exception
     */
    public function actionRemoved()
    {
        $params = Yii::$app->request->queryParams;
        return (new TaskSearch())->search($params, false, false, true);
    }

    /**
     * @OA\Post(
     *      path="/api/task/create",
     *      summary="Create task by order",
     *      tags={"Task"},
     *      description="Method for create task for patient by order",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TaskCreateForm"),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/TaskShortViewDto"),
     *     ),
     * )
     * @throws ErrorValidationException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionCreate(): array
    {
        $data = Yii::$app->request->getBodyParams();

        $model = new TaskCreateForm();
        $model->load($data);

        $result = $model->create();
        if ($model->hasErrors()) {
            throw new ErrorValidationException($model->getFirstErrors());
        }

        return $result;
    }

    /**
     * @OA\Post(
     *      path="/api/task/mark-remove",
     *      summary="Remove task for doctor role",
     *      tags={"Task"},
     *      description="Method for remove task for doctor",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TaskRemoveForm"),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="task_id", type="int", example="100500")
     *         )
     *     ),
     * )
     * @throws ErrorValidationException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionMarkRemove(): array
    {
        $data = Yii::$app->request->getBodyParams();

        $model = new TaskRemoveForm();
        $model->load($data);

        if (!$model->remove()) {
            throw new ErrorValidationException($model->getFirstErrors());
        }

        return ['task_id' => $model->task_id];
    }

    /**
     * @OA\Post(
     *      path="/api/task/mark-done",
     *      summary="Done task for patient role",
     *      tags={"Task"},
     *      description="Method for mark task as done for patient",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TaskDoneForm"),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="task_id", type="int", example="100500")
     *         )
     *     ),
     * )
     * @throws ErrorValidationException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionMarkDone(): array
    {
        $data = Yii::$app->request->getBodyParams();

        $model = new TaskDoneForm();
        $model->load($data);

        if (!$model->done()) {
            throw new ErrorValidationException($model->getFirstErrors());
        }

        return ['task_id' => $model->task_id];
    }

}
