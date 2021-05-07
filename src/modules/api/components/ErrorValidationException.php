<?php

namespace app\modules\api\components;

use Exception;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

class ErrorValidationException extends HttpException
{

    public $errors;

    public function __construct($errors, $message = null, $code = 0, Exception $previous = null)
    {
        $this->errors = $errors;
        $firstKey = key($this->errors);
        parent::__construct(201,$this->errors[$firstKey], $code,$previous);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'ErrorValidationException';
    }
}