<?php

namespace ManipleCore\Doctrine\Extensions;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class TablePrefix
{
    protected $prefix = '';

    public function __construct($prefix)
    {
        $this->prefix = (string) $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $classMetadata */
        $classMetadata = $eventArgs->getClassMetadata();

        $origTableName = $classMetadata->getTableName();

        if (!$classMetadata->isInheritanceTypeSingleTable() || $classMetadata->getName() === $classMetadata->rootEntityName) {
            $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
        }

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide']) {
                $mappedTableName = $mapping['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }

        // add prefix to indexes
        $table = $classMetadata->table;

        if (isset($table['indexes'])) {
            $table['indexes'] = $this->prefixIndexes($table['indexes'], 'idx');
        }

        // create uniqueConstraints from unique columns, so that properly prefixed
        // constraint names can be generated
        foreach ($classMetadata->fieldMappings as $field => &$mapping) {
            if ($mapping['unique']) {
                $table['uniqueConstraints'][] = array(
                    'columns' => array($mapping['columnName']),
                );
                $mapping['unique'] = false;
            }
        }

        if (isset($table['uniqueConstraints'])) {
            $table['uniqueConstraints'] = $this->prefixIndexes($table['uniqueConstraints'], 'uniq');
        }

        // add prefix to sequence
        if ($classMetadata->sequenceGeneratorDefinition) {
            $classMetadata->sequenceGeneratorDefinition['sequenceName'] =
                $this->prefix . $classMetadata->sequenceGeneratorDefinition['sequenceName'];
        }

        $classMetadata->table = $table;
    }

    protected function prefixIndexes(array $array, $typePrefix)
    {
        $prefixedArray = array();

        foreach ($array as $key => $value) {
            if (is_int($key)) {
                // auto gerenerate index name when needed
                $prefixedKey = $this->_generateIdentifierName($value['columns'], $typePrefix);
            } else {
                $prefixedKey = $this->prefix . $key;
            }
            if (isset($array[$prefixedKey])) {
                throw new Exception(sprintf('Prefixed name conflict %s', $prefixedKey));
            }
            $prefixedArray[$prefixedKey] = $value;
        }

        return $prefixedArray;
    }

    protected function _generateIdentifierName($columnNames, $prefix = '', $maxSize = 30)
    {
        $hash = implode("", array_map(function($column) {
            return dechex(crc32($column));
        }, $columnNames));

        return substr($this->prefix . $prefix . "_" . $hash, 0, $maxSize);
    }
}
