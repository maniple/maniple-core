<?php

class ManipleCore_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    public function getModuleDependencies()
    {
        return array();
    }

    public function getResourcesConfig()
    {
        return require __DIR__ . '/configs/resources.config.php';
    }

    public function getRoutesConfig()
    {
        return require __DIR__ . '/configs/routes.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'prefixes' => array(
                'ManipleCore_' => __DIR__ . '/library/',
            ),
        );
    }

    public function getTranslationsConfig()
    {
        return array(
            'scan'    => Zend_Translate::LOCALE_DIRECTORY,
            'content' => __DIR__ . '/languages',
        );
    }

    public function getViewConfig()
    {
        return array(
            'scriptPaths' => __DIR__ . '/views/scripts',
            'helperPaths' => array(
                'ManipleCore_View_Helper_' => __DIR__ . '/library/View/Helper/',
            ),
        );
    }

    public function getAssetsBaseDir()
    {
        return 'core';
    }

    /**
     * Setup view path spec
     */
    protected function _initViewRenderer()
    {
        /** @var Zefram_Controller_Action_Helper_ViewRenderer $viewRenderer */
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setViewScriptPathSpec(':module/:controller/:action.:suffix', 'maniple-core');
        $viewRenderer->setViewSuffix('twig', 'maniple-core');
    }

    /**
     * Initialize and configure errorHandler plugin
     *
     * @return void
     * @throws Zend_Application_Bootstrap_Exception
     */
    protected function _initErrorHandler()
    {
        /** @var Zend_Controller_Front $frontController */
        $frontController = $this->getApplication()->bootstrap('FrontController')->getResource('FrontController');

        if ($frontController->getParam('noErrorHandler')) {
            return;
        }

        /** @var Zend_Controller_Plugin_ErrorHandler $errorHandler */
        $errorHandler = $frontController->getPlugin('Zend_Controller_Plugin_ErrorHandler');
        if (!$errorHandler) {
            // same priority as in Zend_Controller_Front::dispatch()
            $frontController->registerPlugin($errorHandler = new Zend_Controller_Plugin_ErrorHandler(), 100);
        }

        $errorHandler->setErrorHandlerModule('maniple-core');
        $errorHandler->setErrorHandlerController('error');
        $errorHandler->setErrorHandlerAction('error');
    }

    /**
     * @throws Zend_Application_Bootstrap_Exception
     */
    protected function _initLogExtras()
    {
        $bootstrap = $this->getApplication();
        if (!$bootstrap->hasPluginResource('Log')) {
            return;
        }

        /** @var Zend_Log $log */
        $log = $bootstrap->bootstrap('Log')->getResource('Log');

        /** @var Zend_Controller_Front $frontController */
        $frontController = $bootstrap->bootstrap('FrontController')->getResource('FrontController');
        $frontController->registerPlugin(new ManipleCore_Controller_Plugin_LogExtras($log));
    }

    /**
     * If RequireJS service is available, register path to scripts.
     *
     * @see https://github.com/maniple/maniple-requirejs
     * @return void
     */
    protected function _initRequireJS()
    {
        $requirejs = $this->getApplication()->getResource('RequireJS');
        if ($requirejs) {
            $requirejs->addPath('maniple', 'assets/core/js/maniple');
        }
    }
}
