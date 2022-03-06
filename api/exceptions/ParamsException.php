<?php
namespace api\exceptions;

class ParamsException extends HttpException
{
    public $statusCode = 500;//参数错误
}
