<?php
namespace api\exceptions;

class SuccessException extends HttpException
{
    public $statusCode = 200;
}