<?php

class ManipleCore_Bootstrap extends Maniple_Application_Module_Bootstrap
{
    protected $_moduleDeps = array('maniple-vendor-assets');

    public function onBootstrap(Maniple_Application_ModuleBootstrapper $moduleBootstraper)
    {
        $bootstrap = $moduleBootstraper->getBootstrap();

        if ($bootstrap->hasPluginResource('translate') || method_exists($bootstrap, '_initTranslate')) {
            $bootstrap->bootstrap('translate');
            $translate = $bootstrap->getResource('translate');
            $dir = dirname(__FILE__) . '/languages/' . $translate->getLocale();
            if (is_dir($dir)) {
                $bootstrap->getResource('translate')->addTranslation(array(
                    'content' => $dir,
                    'locale'  => $translate->getLocale(),
                ));
            }
        }

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
        return array(
            'core.image_helper' => array(
                'class' => 'ManipleCore_Helper_ImageHelper',
            ),
            'core.file_helper' => array(
                'class' => 'ManipleCore_Helper_FileHelper',
            ),
            'core.model.user_repository' => array(
                'class' => 'ManipleCore_Model_UserRepository',
                'params' => array(
                    'tableProvider' => null,
                    'userClass'     => 'ManipleCore_Model_User',
                ),
            )
        );
    }
}
