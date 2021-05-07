<?php

namespace app\modules\api\forms\user;

use app\models\User;
use borales\extensions\phoneInput\PhoneInputValidator;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class UserSignInForm
 * @OA\Schema(
 *     required={"phone"},
 * )
 * @package app\modules\api\forms\user
 */
class UserSignInForm extends Model
{
    public function formName()
    {
        return '';
    }

    /**
     * @var string
     * @OA\Property(
     *     example="+79012345678"
     * )
     */
    public $phone;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['phone', 'string'],
            ['phone', 'required'],
            [['phone'], PhoneInputValidator::className(), 'region' => ['RU']],
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
    public function sendSms(): ?User
    {
        if ($this->validate()) {
            $model = $this->findUser($this->phone);
            if ($model === null) {
                $model = $this->createUser();
            }
//            $smsCode = random_int(1000, 9999);
            $smsCode = 1111;
            $model->sms_code_confirm = (string) $smsCode;
            $model->save();

//            Yii::$app->sms->send_sms($this->phone, 'Код подтверждения: '.$smsCode);

            return $model;
        }

        return null;
    }

    /**
     * @return User
     */
    private function createUser(): User
    {
        $model = new User();
        $model->setPhone($this->phone);
        $model->generateAuthKey();
        $model->setPassword(md5(time().$model->phone));

        return $model;
    }
}
