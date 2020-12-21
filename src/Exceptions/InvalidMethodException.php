<?php


namespace Jiannei\Enum\Laravel\Exceptions;


use Exception;
use Jiannei\Enum\Laravel\Enum;

class InvalidMethodException extends Exception
{
    /**
     * Create an InvalidMethodException.
     *
     * @param $invalidMethod
     * @param  Enum|string  $enumClass
     */
    public function __construct($invalidMethod, $enumClass)
    {
        $enumClassName = class_basename($enumClass);

        parent::__construct("Cannot found $invalidMethod method on $enumClassName class.", 405);
    }
}