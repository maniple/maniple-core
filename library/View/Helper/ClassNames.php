<?php

class ManipleCore_ClassNames
{
    protected $_classes = array();

    public function __construct()
    {
        $args = func_get_args();
        $this->setClasses($args);
    }

    public function addClass($class)
    {
        $this->_classes[$class] = true;
        return $this;
    }

    public function removeClass($class)
    {
        unset($this->_classes[$class]);
        return $this;
    }

    public function setClasses(array $classes)
    {
        foreach ($classes as $key => $value) {
            if (is_array($value)) {
                $this->setClasses($value);
            } elseif (is_string($value) || $value) {
                $this->_classes[$key] = true;
            } else {
                unset($this->_classes[$key]);
            }
        }
        return $this;
    }

    public function __toString()
    {
        $classes = array();
        foreach ($this->_classes as $key => $value) {
            if ($value) {
                $classes[] = $key;
            }
        }
        return implode(' ', $classes);
    }
}

class ManipleCore_View_Helper_ClassNames extends Zend_View_Helper_Abstract
{
    public function classNames()
    {
        $classes = func_get_args();
        return new ManipleCore_ClassNames($classes);
    }
}
