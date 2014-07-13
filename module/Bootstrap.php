<?php

class ManipleCore_Bootstrap extends Maniple_Application_ModuleBootstrap
{
    public function onBootstrap(Maniple_Application_ModuleBootstrapper $moduleBootstraper)
    {
        $moduleBootstraper->bootstrapModule('maniple-vendor-assets');

        $view = $moduleBootstraper->getBootstrap()->bootstrap('view')->getResource('view');
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
