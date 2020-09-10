<?php


namespace Yetione\Json;

use Yetione\Json\Exceptions\DecodeException;
use Yetione\Json\Exceptions\EncodeException;
use Yetione\Json\Exceptions\ExtensionException;

/**
 * Class Json
 * @package Yetione\Json
 */
class Json
{
    public static int $defaultDepth = 512;

    public static int $defaultEncodeOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

    public static int $defaultDecodeOptions = 0;

    protected static Json $instance;

    protected int $depth;

    protected int $encodeOptions;

    protected int $decodeOptions;

    /**
     * Json constructor.
     * @param int|null $depth
     * @param int|null $encodeOptions
     * @param int|null $decodeOptions
     * @throws ExtensionException
     */
    public function __construct(int $depth=null, int $encodeOptions=null, int $decodeOptions=null)
    {
        static::checkExt();
        $this->depth = $depth ?? static::$defaultDepth;
        $this->encodeOptions = $encodeOptions ?? static::$defaultEncodeOptions;
        $this->decodeOptions = $decodeOptions ?? static::$defaultDecodeOptions;
    }

    /**
     * @param mixed $value
     * @return string
     * @throws EncodeException
     */
    public function encodeValue($value)
    {
        if (is_object($value)) {
            if (($value instanceof Jsonable) || method_exists($value, 'toJson')) {
                return $value->toJson();
            }
//            if (method_exists($value, 'toArray')) {
//                return $this->encodeValue($value->toArray());
//            }
        }
        $json = json_encode($value, $this->encodeOptions, $this->depth);
        if (JSON_ERROR_NONE !== ($error = json_last_error())) {
            throw new EncodeException($value, $error);
        }
        return $json;
    }

    /**
     * @param string $value
     * @param bool $asArray
     * @return mixed
     * @throws DecodeException
     */
    public function decodeValue(string $value, bool $asArray=false)
    {
        $result = json_decode($value, $asArray, $this->depth, $this->decodeOptions);
        if (JSON_ERROR_NONE !== ($error = json_last_error())) {
            throw new DecodeException($value, $error);
        }
        return $result;
    }

    /**
     * Encode PHP value to JSON string.
     * @param mixed $value
     * @return string
     * @throws EncodeException
     * @throws ExtensionException
     */
    public static function encode($value): string
    {
        return static::getInstance()->encodeValue($value);
    }

    /**
     * Decode JSON string to PHP value.
     *
     * @param string $value
     * @param bool $asArray
     * @return mixed
     * @throws DecodeException
     * @throws ExtensionException
     */
    public static function decode(string $value, bool $asArray=false)
    {
        return static::getInstance()->decodeValue($value, $asArray);
    }

    /**
     * @return bool
     * @throws ExtensionException
     */
    protected static function checkExt(): bool
    {
        if (!extension_loaded('json')) {
            throw new ExtensionException();
        }
        return true;
    }

    /**
     * @return static
     * @throws ExtensionException
     */
    protected static function getInstance(): self
    {
        if (!isset(static::$instance)) {
            static::$instance = new static(static::$defaultDepth, static::$defaultEncodeOptions);
        }
        return static::$instance;
    }
}