<?php

abstract class ManipleCore_Service_NavigationManagerFactory
{
    public static function factory($container)
    {
        $manager = new ManipleCore_Navigation_NavigationManager();

        /** @var Zend_View $view */
        $view = $container->getResource('View');
        $view->getHelper('getNavigation')->setNavigationManager($manager);

        return $manager;
    }
}