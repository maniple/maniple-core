<?php

class ManipleCore_Prefs_PrefType_Validate extends ManipleCore_Prefs_PrefType
{
    /**
     * @var Zend_Validate_Abstract
     */
    protected $_validator;

    /**
     * Constructor.
     *
     * @param  string $type
     * @param  Zend_Validate_Abstract $validator
     * @param  mixed $default OPTIONAL
     * @return void
     */
    public function __construct($type, Zend_Validate_Abstract $validator, $default = null)
    {
        parent::__construct($type, $default);

        $this->_validator = $validator;
    }

    /**
     * Check if given value passes validator validation.
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        return parent::isValid($value)
            && $this->_validator->isValid($value);
    }
}
