<?php

class ManipleCore_View_Helper_GetNavigation extends Zend_View_Helper_Abstract
{
    /** @var ManipleCore_Navigation_NavigationManager */
    protected $_navigationManager;

    /**
     * @param ManipleCore_Navigation_NavigationManager $manager
     * @return $this
     */
    public function setNavigationManager(ManipleCore_Navigation_NavigationManager $manager)
    {
        $this->_navigationManager = $manager;
        return $this;
    }

    /**
     * @return ManipleCore_Navigation_NavigationManager
     * @throws Zend_View_Exception
     */
    public function getNavigationManager()
    {
        if (!$this->_navigationManager) {
            throw new Zend_View_Exception('Navigation manager is unavailable');
        }
        return $this->_navigationManager;
    }

    /**
     * @param string $name
     * @return Zend_Navigation|null
     */
    public function getNavigation($name)
    {
        return $this->getNavigationManager()->getNavigation($name);
    }
}
