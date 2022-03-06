<?php
namespace api\exceptions;

class HttpException extends \yii\web\HttpException
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($this->statusCode, $message, $code, $previous);
    }
}