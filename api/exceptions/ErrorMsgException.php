<?php
namespace api\exceptions;

class ErrorMsgException extends HttpException
{
    public $statusCode = 500;
}
