<?php

namespace Racoon\Router\Exception;


class RouterException extends Exception
{

    public function __construct($request = null, $message, \Exception $previous = null)
    {
        parent::__construct($request, false, $message, 500, $previous);
    }

}