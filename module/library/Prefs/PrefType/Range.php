<?php

class ManipleCore_Prefs_PrefType_Range extends ManipleCore_Prefs_PrefType
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
        settype($min, $this->_type);
        settype($max, $this->_type);

        if ($min > $max) {
            $this->_min = $max;
            $this->_max = $min;
        } else {
            $this->_min = $min;
            $this->_max = $max;
        }

        parent::__construct($type, $default);
    }

    /**
     * Check if given value is within required range.
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        return parent::isValid($value)
            && $this->_min <= $value && $value <= $this->_max;
    }
}
