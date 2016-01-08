<?php

class ManipleCore_Navigation_NavigationManager
{
    /**
     * @var Zend_Navigation[]
     */
    protected $_containers;

    /**
     * @param string $name
     * @param array|Zend_Config $pages
     * @return $this
     */
    public function registerNavigation($name, $pages = null)
    {
        $this->_containers[$name] = new Zend_Navigation($pages);
        return $this;
    }

    /**
     * @param string $name
     * @return Zend_Navigation|null
     */
    public function getNavigation($name)
    {
        return isset($this->_containers[$name]) ? $this->_containers[$name] : null;
    }
}
