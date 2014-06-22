<?php

class ManipleCore_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function getResourcesConfig()
    {
        return array(
            'core.image_helper' => array(
                'class' => 'ManipleCore_Helper_ImageHelper',
            ),
            'core.file_helper' => array(
                'class' => 'ManipleCore_Helper_FileHelper',
            ),
        );
    }
}
