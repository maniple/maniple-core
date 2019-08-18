<?php

class ManipleCore_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    public function getModuleDependencies()
    {
        return array();
    }

    public function getResourcesConfig()
    {
        return require dirname(__FILE__) . '/configs/resources.config.php';
    }

    public function getRoutesConfig()
    {
        return require dirname(__FILE__) . '/configs/routes.config.php';
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
