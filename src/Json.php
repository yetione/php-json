<?php


namespace Yetione\Json;

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
     * @throws JsonException
     */
    public function encodeValue($value)
    {
        $json = json_encode($value, $this->encodeOptions, $this->depth);

        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE) {
            throw JsonException::encoding($value, $error);
        }

        return $json;
    }

    /**
     * @param string $value
     * @param bool $asArray
     * @return mixed
     * @throws JsonException
     */
    public function decodeValue(string $value, bool $asArray=false)
    {
        $result = json_decode($value, $asArray, $this->depth, $this->decodeOptions);
        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE) {
            throw JsonException::decoding($result, $error);
        }
        return $result;
    }

    /**
     * Encode PHP value to JSON string.
     * @param mixed $value
     * @return string
     * @throws JsonException
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
     * @throws JsonException
     */
    public static function decode(string $value, bool $asArray=false)
    {
        return static::getInstance()->decodeValue($value, $asArray);
    }

    protected static function checkExt(): bool
    {
        if (!extension_loaded('json')) {
            throw JsonException::extensionIsMissing();
        }
        return true;
    }

    protected static function getInstance(): self
    {
        if (!isset(static::$instance)) {
            static::$instance = new static(static::$defaultDepth, static::$defaultEncodeOptions);
        }
        return static::$instance;
    }
}