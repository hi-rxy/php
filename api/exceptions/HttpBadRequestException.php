<?php
namespace api\exceptions;

class HttpBadRequestException extends HttpException
{
    public $statusCode = 400;
}