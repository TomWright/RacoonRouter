<?php

namespace Racoon\Router\Exception;


class NotFoundException extends Exception
{

    public function __construct($request = null, $message, $code = 404, \Exception $previous = null)
    {
        parent::__construct($request, true, $message, $code, $previous);
    }

}