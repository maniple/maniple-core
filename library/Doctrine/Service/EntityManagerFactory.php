<?php

namespace ManipleCore\Doctrine\Service;

abstract class EntityManagerFactory
{
    public function createService(\Zefram_Application_ResourceContainer $container)
    {
        /** @var $db \Zefram_Db */
        $db = $container->getResource('ZeframDb');

        $evm = new \Doctrine\Common\EventManager();
        $conn = \Doctrine\DBAL\DriverManager::getConnection(array('pdo' => $db->getAdapter()->getConnection()), null, $evm);

        $logger = new \Doctrine\DBAL\Logging\DebugStack();
        $conn->getConfiguration()->setSQLLogger($logger);

        $paths = array(
            // __DIR__ . '/../../config/doctrine'
            __DIR__ . '/../Model',
        );
        $isDevMode = true;
        if(0)$config = \Doctrine\ORM\Tools\Setup::createYAMLMetadataConfiguration(
            $paths,
            $isDevMode
        );
        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $config->setProxyDir(APPLICATION_PATH . '/../data/doctrine/Proxies');
        $config->setAutoGenerateProxyClasses(true);

        // setup table prefix
        $tablePrefix = new \ManipleCore\Doctrine\Extensions\TablePrefix($db->getTablePrefix());
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

        // setup custom types
        \Doctrine\DBAL\Types\Type::addType('epoch', 'ManipleCore\Doctrine\Types\Epoch');

        $entityManager = \Doctrine\ORM\EntityManager::create($conn, $config, $evm);
        return $entityManager;
    }
}
