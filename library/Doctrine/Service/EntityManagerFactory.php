<?php

namespace ManipleCore\Doctrine\Service;

abstract class EntityManagerFactory
{
    public static function factory(\Zefram_Application_ResourceContainer $container)
    {
        /** @var \ManipleCore\Doctrine\Config $config */
        $config = $container->getResource('EntityManager.config');

        /** @var $db \Zefram_Db */
        $db = $container->getResource('ZeframDb');

        $evm = new \Doctrine\Common\EventManager();
        $conn = \Doctrine\DBAL\DriverManager::getConnection(array('pdo' => $db->getAdapter()->getConnection()), null, $evm);

        $logger = new \Doctrine\DBAL\Logging\DebugStack();
        $conn->getConfiguration()->setSQLLogger($logger);

        $paths = $config->getPaths();
        $isDevMode = true;
        if(0)$config = \Doctrine\ORM\Tools\Setup::createYAMLMetadataConfiguration(
            $paths,
            $isDevMode
        );
        $metadataConfig = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $metadataConfig->setProxyDir(APPLICATION_PATH . '/../data/doctrine/Proxies');
        $metadataConfig->setAutoGenerateProxyClasses(true);

        // setup table prefix
        $tablePrefix = new \ManipleCore\Doctrine\Extensions\TablePrefix($db->getTablePrefix());
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

        // setup custom types
        \Doctrine\DBAL\Types\Type::addType('epoch', 'ManipleCore\Doctrine\Types\Epoch');

        foreach ($config->getTypes() as $name => $class) {
            \Doctrine\DBAL\Types\Type::addType($name, $class);
        }

        $entityManager = \Doctrine\ORM\EntityManager::create($conn, $metadataConfig, $evm);
        return $entityManager;
    }
}
