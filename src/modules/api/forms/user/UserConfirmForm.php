<?php

namespace app\modules\api\forms\user;

use app\models\User;
use borales\extensions\phoneInput\PhoneInputValidator;
use Exception;
use yii\base\Model;

/**
 * Class UserConfirmForm
 * @OA\Schema(
 *     required={"phone", "code"},
 * )
 *
 * @package app\modules\api\forms\user
 */
class UserConfirmForm extends Model
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
     *     example="+79012345678"
     * )
     *
     */
    public $phone;

    /**
     * @var string
     *
     * @OA\Property(
     *     example="1234"
     * )
     *
     */
    public $code;

    /**
     * @var string
     * @OA\Property(
     *     example="Иван"
     * )
     *
     */
    public $first_name;

    /**
     * @var string
     * @OA\Property(
     *     example="Васильевич"
     * )
     *
     */
    public $middle_name;

    /**
     * @var string
     * @OA\Property(
     *     example="Пупкин"
     * )
     *
     */
    public $last_name;

    /**
     * @var string
     * @OA\Property(
     *     example="male"
     * )
     *
     */
    public $gender;

    /**
     * @var string
     * @OA\Property(
     *     example="1990-12-25"
     * )
     *
     */
    public $birthday;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['phone', 'code', 'first_name', 'last_name', 'middle_name', 'gender'], 'string'],
            ['birthday', 'date', 'format' => 'Y-m-d'],
            [['phone', 'code'], 'required'],
            [['phone'], PhoneInputValidator::class, 'region' => ['RU']],
        ];
    }

    /**
     * @param string $phone
     * @return User|null
     */
    public function findUser(string $phone): ?User
    {
        $phone = User::cleanPhone($phone);

        return User::find()->andWhere(['phone' => $phone])->one();
    }

    /**
     * @throws Exception
     */
    public function confirmUser(): ?User
    {
        if (!$this->validate()) {
            return null;
        }
        $model = $this->findUser($this->phone);
        if ($model === null || !$model->isEqualSmsCode($this->code)) {
            throw new Exception('Code incorrect ');
        }
        $model->generateConfirmedAt();
        if ($this->first_name) {
            $model->first_name = $this->first_name;
        }
        if ($this->last_name) {
            $model->last_name = $this->last_name;
        }
        if ($this->middle_name) {
            $model->middle_name = $this->middle_name;
        }
        if ($this->gender) {
            $model->gender = $this->gender;
        }
        if ($this->birthday) {
            $model->birthday = $this->birthday;
        }
        $model->save();

        return $model;
    }

}
