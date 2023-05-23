<?php

namespace EasyAPI\Exceptions;
use Exception;

class HttpException extends Exception
{
    public function __construct($message = 'Internal Server Error', $status_http_code = 500, Exception $previous = null) {
        parent::__construct($message, $status_http_code, $previous);
    }
}