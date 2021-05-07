<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 * @property int         $id
 * @property string      $phone
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property string|null $gender
 * @property string|null $birthday
 * @property string|null $sms_code_confirm
 * @property string      $auth_key
 * @property string      $password_hash
 * @property string|null $password_reset_token
 * @property int         $status_id
 * @property int|null    $created_at
 * @property int|null    $updated_at
 * @property int|null    $confirmed_at
 * @property int|null    $role_id
 */
class User extends ActiveRecord implements IdentityInterface
{

    public const STATUS_NEW = 0;

    public const STATUS_ACTIVE = 1;

    public const ROLE_ADMIN = 0;

    public const ROLE_PATIENT = 1;

    public const ROLE_DOCTOR = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['phone', 'auth_key', 'password_hash'], 'required'],
            [['birthday'], 'safe'],
            ['status_id', 'default', 'value' => self::STATUS_NEW],
            ['role_id', 'default', 'value' => self::ROLE_PATIENT],
            [['status_id', 'created_at', 'updated_at', 'confirmed_at', 'role_id'], 'integer'],
            [['phone', 'first_name', 'middle_name', 'last_name', 'gender', 'sms_code_confirm', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['phone'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @return string[]
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'birthday' => 'Birthday',
            'sms_code_confirm' => 'Sms Code Confirm',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'status_id' => 'Status ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'confirmed_at' => 'Confirmed At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\query\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\query\UserQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status_id' => self::STATUS_ACTIVE]);
    }

    /**
     * @param mixed $token
     * @param null  $type
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Finds user by username
     * @param string $username
     * @param int    $status
     * @return static|null
     */
    public static function findByUsername(string $username, int $status = self::STATUS_ACTIVE): ?User
    {
        return static::findOne(['phone' => $username, 'status_id' => $status]);
    }

    /**
     * Finds user by password reset token
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status_id' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status_id' => self::STATUS_NEW,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param $phone
     * @return User
     */
    public function setPhone($phone): self
    {
        $cleanedPhone = self::cleanPhone($phone);
        $this->phone = $cleanedPhone;

        return $this;
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString().'_'.time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param $phone
     * @return string
     */
    public static function cleanPhone($phone): string
    {
        return str_replace(['(', ')', ' '], '', $phone);
    }

    /**
     * @param string $code
     * @return bool
     */
    public function isEqualSmsCode(string $code): bool
    {
        return $this->sms_code_confirm === $code;
    }

    /**
     * @return $this
     */
    public function generateConfirmedAt(): self
    {
        $this->confirmed_at = time();
        $this->status_id = self::STATUS_ACTIVE;

        return $this;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function checkUserCredentials($username, $password): bool
    {
        if (!$user = self::find()->byPhone($username)->one()) {
            return false;
        }

        return $user->validatePassword($password);
    }

    /**
     * @param string $username
     * @return array
     */
    public function getUserDetails($username): array
    {
        $user = self::find()->byPhone($username)->one();

        return ['user_id' => $user->id];
    }

    /**
     * @return string[]
     */
    public static function getRoleList(): array
    {
        return [
            self::ROLE_ADMIN => 'admin',
            self::ROLE_PATIENT => 'patient',
            self::ROLE_DOCTOR => 'doctor',
        ];
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        $roleList = self::getRoleList();

        return $roleList[$this->role_id];
    }
}
