<?php


namespace Yetione\Json\Exceptions;


class ExtensionException extends JsonException
{
    private const JSON_EXT_NOT_FOUND = 99;

    public function __construct($previous=null)
    {
        parent::__construct('ext-json is missing', self::JSON_EXT_NOT_FOUND, $previous);
    }
}