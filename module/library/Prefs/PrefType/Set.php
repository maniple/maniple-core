<?php

class ManipleCore_Prefs_PrefType_Set extends ManipleCore_Prefs_PrefType
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
        foreach ($values as $key => $value) {
            settype($value, $this->_type);
            $values[$key] = $value;
        }

        $values = array_unique($values);

        if (empty($values)) {
            throw new InvalidArgumentException('Empty values array provided');
        }

        $this->_values = $values;

        parent::__construct($type, $default);
    }

    /**
     * Check if given value belongs to the set.
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        return parent::isValid($value)
            && in_array($value, $this->_values, true);
    }
}
