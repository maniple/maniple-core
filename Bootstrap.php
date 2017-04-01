<?php

class ManipleCore_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    protected $_moduleTasks = array('translations');

    public function getModuleDependencies()
    {
        return array('maniple-vendor-assets');
    }

    public function onBootstrap(Maniple_Application_ModuleBootstrapper $moduleBootstraper)
    {
        $bootstrap = $moduleBootstraper->getBootstrap();

        $bootstrap->bootstrap('request');
        $bootstrap->bootstrap('view');

        $view = $bootstrap->getResource('view');
        $view->headScript()->appendScript(sprintf(
            'require.config(%s)',
            Zefram_Json::encode(
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

    public function getAssetsBaseDir()
    {
        return 'core';
    }

    public function getResourcesConfig()
    {
        return require dirname(__FILE__) . '/configs/resources.config.php';
    }

    protected function _initRouter()
    {
        /** @var Zend_Controller_Router_Rewrite $router */
        $router = $this->getApplication()->getResource('FrontController')->getRouter();
        $router->addConfig(new Zend_Config(
            require dirname(__FILE__) . '/configs/routes.config.php'
        ));
    }

    protected function _initView()
    {
        $bootstrap = $this->getApplication();
        $bootstrap->bootstrap('View');

        /** @var Zend_View $view */
        $view = $bootstrap->getResource('View');
        $view->addHelperPath(dirname(__FILE__) . '/library/View/Helper/', 'ManipleCore_View_Helper_');
    }
}
