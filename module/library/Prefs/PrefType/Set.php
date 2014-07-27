<?php

class ManipleCore_Prefs_PrefType_Set
{
    protected $_values;

    /**
     * Construct.
     *
     * @param  string $type
     * @param  array $values
     * @param  mixed $default OPTIONAL
     * @return void
     */
    public function __construct($type, array $values, $default = null)
    {
        parent::__construct($type, $default);

        foreach ($values as $key => $value) {
            settype($value, $this->_type);
            $values[$key] = $value;
        }

        $values = array_unique($values);

        if (empty($values)) {
            throw new InvalidArgumentException('Empty values array provided');
        }

        if ($this->_default !== null &&
            !in_array($this->_default, $values, true)
        ) {
            throw new RangeException('Default value was not found in values array');
        }

        $this->_values = $values;
    }

    /**
     * @param  mixed $value
     * @return mixed
     */
    public function getValue($value)
    {
        settype($value, $this->_type);

        if (!in_array($value, $this->_values, true)) {
            return $this->_default;
        }

        return $value;
    }
}
