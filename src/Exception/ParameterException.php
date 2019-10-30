<?php


namespace Ycsk\Definedform\Exception;


class ParameterException extends Exception
{
    public function __construct($message = "", $code = self::PARAMETER_ERR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}