<?php

abstract class ManipleCore_Service_NavigationManagerFactory
{
    public static function factory($container)
    {
        $manager = new ManipleCore_Navigation_NavigationManager();

        /** @var Zend_View $view */
        $view = $container->getResource('View');

        $helper = new ManipleCore_View_Helper_GetNavigation();
        $helper->setView($view);
        $helper->setNavigationManager($manager);

        $view->registerHelper($helper, 'getNavigation');

        return $manager;
    }
}
