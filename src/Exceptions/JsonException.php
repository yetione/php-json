<?php


namespace Yetione\Json\Exceptions;


use RuntimeException;
use Throwable;

abstract class JsonException extends RuntimeException implements Throwable
{
    public static array $errors = [
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        JSON_ERROR_UTF16 => 'Malformed UTF-16 characters, possibly incorrectly encoded',
        JSON_ERROR_RECURSION => 'One or more recursive references in the value to be encoded',
        JSON_ERROR_INF_OR_NAN => 'One or more NAN or INF values in the value to be encoded',
        JSON_ERROR_UNSUPPORTED_TYPE => 'Value of a type that cannot be encoded was given',
        JSON_ERROR_INVALID_PROPERTY_NAME => 'A property name that cannot be encoded was given',
    ];

    public $value;

    public function __construct($value, int $code, $previous=null)
    {
        $this->value = $value;
        parent::__construct(self::$errors[$code] ?? 'Unknown error', $code, $previous);
    }
}