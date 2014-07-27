<?php

class ManipleCore_Prefs_PrefType_Range
{
    protected $_min;

    protected $_max;

    /**
     * Constructor.
     *
     * @param  string $type
     * @param  mixed $min
     * @param  mixed $max
     * @param  mixed $default OPTIONAL
     * @return void
     */
    public function __construct($type, $min, $max, $default = null)
    {
        parent::__construct($type, $default);

        settype($min, $this->_type);
        settype($max, $this->_type);

        if ($min > $max) {
            $this->_min = $max;
            $this->_max = $min;
        } else {
            $this->_min = $min;
            $this->_max = $max;
        }

        if ($this->_default !== null &&
            ($this->_default < $this->_min || $this->_max < $this->_default)
        ) {
            throw new RangeException('Default value is outside allowed range');
        }
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    public function getValue($value)
    {
        settype($value, $this->_type);

        if ($value < $this->_min || $this->_max < $value) {
            return $this->_default;
        }

        return $value;
    }
}
