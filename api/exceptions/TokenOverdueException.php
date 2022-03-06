<?php
namespace api\exceptions;

class TokenOverdueException extends HttpException
{
    public $statusCode = 501;//token过期
}
