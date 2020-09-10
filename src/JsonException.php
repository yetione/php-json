<?php


namespace Yetione\Json;


use RuntimeException;
use Throwable;

/**
 * Class JsonException
 * @package Yetione\Json
 */
class JsonException extends RuntimeException
{
    public string $decodedJson;
    public string $encodedValue;

    private const JSON_EXT_NOT_FOUND = 99;

    /** @var array json_last_error() code/message map */
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

    /**
     * Static constructor for decoding errors.
     *
     * @param  mixed $value
     * @param  int $code
     * @param  Throwable $previous
     * @return JsonException
     */
    public static function encoding($value, int $code, $previous = null)
    {
        $e = static::instance($code, $previous);
        $e->encodedValue = $value;

        return $e;
    }

    /**
     * Static constructor for decoding errors.
     *
     * @param  string $json
     * @param  int $code
     * @param  Throwable $previous
     * @return JsonException
     */
    public static function decoding(string $json, int $code, $previous = null)
    {
        $e = static::instance($code, $previous);
        $e->decodedJson = $json;

        return $e;
    }

    public static function extensionIsMissing($previous=null)
    {
        return new self('ext-json is missing', self::JSON_EXT_NOT_FOUND, $previous);
    }

    /**
     * Constructor for JsonException instance.
     *
     * @param  int $code
     * @param  Throwable $previous
     * @return JsonException
     */
    private static function instance(int $code, $previous = null)
    {
        return new self(self::$errors[$code] ?? 'Unknown error', $code, $previous);
    }
}