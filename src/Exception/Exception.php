<?php

namespace Racoon\Router\Exception;


class Exception extends \Exception
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var bool
     */
    protected $displayAsError;

    public function __construct($request = null, $displayAsError = false, $message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setRequest($request);
        $this->setDisplayAsError($displayAsError == true);
    }


    /**
     * @return object|null
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * @param $request
     */
    public function setRequest($request = null)
    {
        $this->request = $request;
    }


    /**
     * @return boolean
     */
    public function shouldDisplayAsError()
    {
        return $this->displayAsError;
    }


    /**
     * @param boolean $displayAsError
     */
    public function setDisplayAsError($displayAsError)
    {
        $this->displayAsError = $displayAsError;
    }

}