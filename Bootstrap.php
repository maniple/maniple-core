<?php

class ManipleCore_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    public function getModuleDependencies()
    {
        return array('maniple-vendor-assets');
    }

    public function getResourcesConfig()
    {
        return require dirname(__FILE__) . '/configs/resources.config.php';
    }

    public function getRoutesConfig()
    {
        return require dirname(__FILE__) . '/configs/routes.config.php';
    }

    public function getTranslationsConfig()
    {
        return array(
            'scan'    => Zend_Translate::LOCALE_DIRECTORY,
            'content' => dirname(__FILE__) . '/languages',
        );
    }

    public function getViewConfig()
    {
        return array(
            'helperPaths' => array(
                'ManipleCore_View_Helper_' => dirname(__FILE__) . '/library/View/Helper/',
            ),
        );
    }

    public function getAssetsBaseDir()
    {
        return 'core';
    }

    protected function _initView()
    {
        $application = $this->getApplication();

        $application->bootstrap('request');
        $application->bootstrap('view');

        $view = $application->getResource('view');
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
}
