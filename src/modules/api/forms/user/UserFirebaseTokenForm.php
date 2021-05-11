<?php

namespace app\modules\api\forms\user;

use app\models\User;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class UserFirebaseTokenForm
 * @OA\Schema(
 *     required={"firebase_token"},
 * )
 *
 * @package app\modules\api\forms\user
 */
class UserFirebaseTokenForm extends Model
{
    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @var string
     *
     * @OA\Property(
     *     example="asdasdq4124afasd"
     * )
     *
     */
    public $firebase_token;


    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['firebase_token'], 'string'],
            [['firebase_token'], 'required'],
        ];
    }

    /**
     * @throws Exception
     */
    public function save(): ?User
    {
        if (!$this->validate()) {
            return null;
        }
        $user = Yii::$app->user->identity;
        $user->firebase_token = $this->firebase_token;
        $user->save();

        return $user;
    }

}
