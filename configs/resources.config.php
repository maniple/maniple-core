<?php return array(
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
    ),
    'core.navigation_manager' => array(
        'callback' => 'ManipleCore_Service_NavigationManagerFactory::factory',
    ),

    'EntityManager' => array(
        'callback' => 'ManipleCore\Doctrine\Service\EntityManagerFactory::factory',
    ),
);