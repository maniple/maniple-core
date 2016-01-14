<?php

class ManipleCore_Validate_FilteredStringLength extends Zend_Validate_StringLength
{
    protected $_filter;

    public function __construct(array $options)
    {
        parent::__construct($options);

        if (array_key_exists('filter', $options)) {
            $this->setFilter($options['filter']);
        }
    }

    public function setFilter($filter = null)
    {
        $this->_filter = $filter;
        return $this;
    }

    public function getFilter()
    {
        return $this->_filter;
    }

    public function isValid($value)
    {
        $filter = $this->getFilter();
        if ($filter) {
            $value = $filter->filter($value);
        }
        return parent::isValid($value);
    }
}
