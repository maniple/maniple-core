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

    'EntityManager.config' => array(
        'class' => 'ManipleCore\Doctrine\Config',
    ),

    'mapperProvider' => array(
        'callback' => 'ManipleCore_Service_DbFactory::createDbMapperProvider',
    ),
    'db.mapper_provider' => 'resource:mapperProvider',
    'db.table_provider'  => array(
        'callback' => 'ManipleCore_Service_DbFactory::createDbTableProvider',
    ),
    'db.adapter' => array(
        'callback' => 'ManipleCore_Service_DbFactory::createDbAdapter',
    ),

    'Maniple.Queue' => array(
        'class' => 'ManipleCore_Queue_Service',
        'args'  => array(
            'resource:Zefram_Db',
            'resource:SharedEventManager',
            'resource:Log',
        ),
    ),
);
