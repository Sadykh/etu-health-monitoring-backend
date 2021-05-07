<?php

namespace app\modules\api\controllers;

use app\models\Order;
use app\modules\api\components\ErrorValidationException;
use app\modules\api\forms\user\UserConfirmForm;
use app\modules\api\forms\user\UserSignInForm;
use Exception;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\rest\Controller;

class UserController extends Controller
{
    /**
     *
     * @OA\Post(
     *      path="/api/user/sign-in",
     *      summary="Sign in",
     *      tags={"User"},
     *      description="Method for sing in user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserSignInForm"),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="is_exist_user", type="boolean"),
     *         )
     *     ),
     * )
     *
     *
     * @throws ErrorValidationException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionSignIn(): array
    {
        $data = Yii::$app->request->getBodyParams();

        $model = new UserSignInForm();
        $model->load($data);

        $user = $model->sendSms();
        if ($user === null) {
            throw new ErrorValidationException($model->getFirstErrors());
        }

        return [
            'is_exist_user' => $user->confirmed_at !== null,
        ];
    }

    /**
     * @throws ErrorValidationException
     * @throws InvalidConfigException
     * @throws Exception
     *
     * @OA\Post(
     *      path="/api/user/confirm",
     *      summary="Confirm",
     *      tags={"User"},
     *      description="Method for confirn sign in or sign up user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserConfirmForm"),
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="first_name", type="string"),
     *              @OA\Property(property="last_name", type="string"),
     *              @OA\Property(property="middle_name", type="string"),
     *              @OA\Property(property="gender", type="string"),
     *              @OA\Property(property="birthday", type="string", example="1990-12-25"),
     *              @OA\Property(property="auth_key", type="string"),
     *         )
     *     ),
     * )
     */
    public function actionConfirm(): array
    {
        $data = Yii::$app->request->getBodyParams();

        $model = new UserConfirmForm();
        $model->load($data);

        $user = $model->confirmUser();
        if ($user === null) {
            throw new ErrorValidationException($model->getFirstErrors());
        }

        return [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'middle_name' => $user->middle_name,
            'gender' => $user->gender,
            'birthday' => $user->birthday,
            'auth_key' => $user->auth_key,
        ];
    }

    /**
     * @return array
     * @OA\Get(
     *      path="/api/user/profile",
     *      summary="Profile info",
     *      tags={"User"},
     *      description="Method for get profile info",
     *      @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="first_name", type="string"),
     *              @OA\Property(property="last_name", type="string"),
     *              @OA\Property(property="middle_name", type="string"),
     *              @OA\Property(property="gender", type="string"),
     *              @OA\Property(property="birthday", type="string", example="1990-12-25"),
     *              @OA\Property(property="is_ill", type="boolean"),
     *         )
     *     ),
     * )
     *
     */
    public function actionProfile(): array
    {
        $user = Yii::$app->user->identity;
        $doctor = null;

        $order = Order::find()->byPatient($user->getId())->byStatusWorking()->one();
        if ($order !== null) {
            $orderDoctor = $order->doctor;
            $doctor = [
                'first_name' => $orderDoctor->first_name,
                'last_name' => $orderDoctor->last_name,
                'middle_name' => $orderDoctor->middle_name,
                'phone' => $orderDoctor->phone,
            ];
        }
        return [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'middle_name' => $user->middle_name,
            'gender' => $user->gender,
            'birthday' => $user->birthday,
            'is_ill' => $order !== null,
            'doctor' => $doctor
        ];
    }
}
