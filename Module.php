<?php

namespace ManipleCore;

use Zend\Mvc\MvcEvent;

class Module
{
    public function getModuleDependencies()
    {
        return array('ManipleVendorAssets');
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'prefixes' => array(
                    'ManipleCore_' => __DIR__ . '/library',
                ),
            ),
        );
    }

    public function getConfig()
    {
        return array_merge(
            require __DIR__ . '/configs/resources.config.php',
            array(
                'resources' => array(
                    'view' => array(
                        'helperPath' => array(
                            'ManipleCore_View_Helper_' => __DIR__ . '/library/View/Helper',
                        ),
                    ),
                ),
            )
        );
    }

    public function getAssetsBaseDir()
    {
        return 'core';
    }

    public function onBootstrap(MvcEvent $e)
    {
        /** @var $bootstrap \Zend_Application_Bootstrap_Bootstrap */
        $bootstrap = $e->getApplication()->getServiceManager()->get('Bootstrap');

        /** @var $view \Zend_View_Abstract */
        $view = $bootstrap->getResource('View');
        $view->headScript()->appendScript(sprintf(
            'require.config(%s)',
            \Zefram_Json::encode(
                array(
                    'paths' => array(
                        'maniple' => $view->baseUrl('/assets/core/js/maniple'),
                    ),
                ),
                array(
                    'unescapedSlashes' => true,
                    'unescapedUnicode' => true,
                )
            )
        ));
    }
}