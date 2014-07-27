<?php

class ManipleCore_Prefs_PrefType
{
    const TYPE_BOOL   = 'bool';
    const TYPE_INT    = 'int';
    const TYPE_FLOAT  = 'float';
    const TYPE_STRING = 'string';

    /**
     * @var string
     */
    protected $_type;

    /**
     * @var mixed
     */
    protected $_default;

    /**
     * Constructor.
     *
     * @param  string $type
     * @param  mixed $default
     * @return void
     */
    function __construct($type, $default = null)
    {
        switch ($type) {
            case TYPE_BOOL:
            case TYPE_INT:
            case TYPE_FLOAT:
            case TYPE_STRING:
                $this->_type = $type;
                break;

            default:
                throw new InvalidArgumentException('Invalid type specified: ' . $type);
        }

        if ($default !== null) {
            settype($default, $this->_type);
            $this->_default = $default;
        }
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    public function getValue($value)
    {
        settype($value, $this->_type);
        return $value;
    }

    /**
     * @param  bool $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType
     */
    public static function BoolValue($default = null)
    {
        return new self(self::TYPE_BOOL, $default);
    }

    /**
     * @param  int $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType
     */
    public static function IntValue($default = null)
    {
        return new self(self::TYPE_INT, $default);
    }

    /**
     * @param  int $min
     * @param  int $max
     * @param  int $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType_Range
     */
    public static function IntRange($min, $max, $default = null)
    {
        return new ManipleCore_Prefs_PrefType_Range(self::TYPE_INT, $min, $max, $default);
    }

    /**
     * @param  int[] $values
     * @param  int $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType_Set
     */
    public static function IntSet(array $values, $default = null)
    {
        return new ManipleCore_Prefs_PrefType_Set(self::TYPE_INT, $values, $default);
    }

    /**
     * @param  string $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType
     */
    public static function StringValue($default = null)
    {
        return new self(self::TYPE_STRING, $default);
    }

    /**
     * @param  string $min
     * @param  string $max
     * @param  string $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType_Range
     */
    public static function StringRange($min, $max, $default = null)
    {
        return new ManipleCore_Prefs_PrefType_Range(self::TYPE_STRING, $min, $max, $default);
    }

    /**
     * @param  string[] $values
     * @param  string $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType_Set
     */
    public static function StringSet(array $values, $default = null)
    {
        return new ManipleCore_Prefs_PrefType_Set(self::TYPE_STRING, $values, $default);
    }

    /**
     * @param  float $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType
     */
    public static function FloatValue($default = null)
    {
        return new self(self::TYPE_FLOAT, $default);
    }

    /**
     * @param  float $min
     * @param  float $max
     * @param  float $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType_Range
     */
    public static function FloatRange($min, $max, $default = null)
    {
        
    }

    /**
     * @param  float[] $values
     * @param  float $default OPTIONAL
     * @return ManipleCore_Prefs_PrefType_Set
     */
    public static function FloatSet(array $values, $default = null)
    {}
}
