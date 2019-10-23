<?php

class ManipleCore_Service_DbFactory
{
    public static function factory($container)
    {
        $config = $container->Config;

        if (empty($config['db']['adapter'])) {
            throw new Exception('Database adapter config is not provided');
        }

        $params = $config['db']['params'];
        // $params['adapterNamespace'] = 'Zefram_Db_Adapter';

        $db = Zefram_Db::factory($config['db']['adapter'], $params);

        if (isset($config['db']['table_prefix'])) {
            $db->setTablePrefix($config['db']['table_prefix']);
        }

        Zend_Db_Table::setDefaultAdapter($db->getAdapter());
        Zend_Db_Table::setDefaultMetadataCache($container->Cache);

        try {
            $db->getAdapter()->getServerVersion();
            if (isset($params['driver_options']['init_command'])) {
                $db->getAdapter()->query($params['driver_options']['init_command']);
            }

        } catch (Exception $e) {
            throw new Exception('Unable to connect to database!');
        }

        return $db;
    } // }}}

    /**
     * @param $container
     * @return Zend_Db_Adapter_Abstract
     */
    public static function createDbAdapter($container)
    {
        /** @var Zefram_Db $db */
        $db = $container->Zefram_Db;

        return $db->getAdapter();
    }

    /**
     * @param $container
     * @return Maniple_Model_Db_MapperProvider
     */
    public static function createDbMapperProvider($container)
    {
        /** @var Zefram_Db $db */
        $db = $container->Zefram_Db;
        $mapperProvider = new Maniple_Model_Db_MapperProvider($db->getAdapter());
        $mapperProvider->getTableProvider()->setTablePrefix($db->getTablePrefix());

        return $mapperProvider;
    }

    /**
     * @param $container
     * @return Maniple_Model_Db_TableProvider
     */
    public static function createDbTableProvider($container)
    {
        /** @var Maniple_Model_Db_MapperProvider $mapperProvider */
        $mapperProvider = $container->mapperProvider;

        return $mapperProvider->getTableProvider();
    }
}
