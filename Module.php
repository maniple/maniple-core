<?php

namespace ManipleCore;

use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return require __DIR__ . '/configs/resources.config.php';
    }

    public function getAssetsBaseDir()
    {
        return 'core';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();

        /** @var $view \Zend_View_Abstract */
        $view = $sm->get('View');
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