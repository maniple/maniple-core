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
            $table['indexes'] = $this->prefixArrayKeys($table['indexes']);
        }

        if (isset($table['uniqueConstraints'])) {
            $table['uniqueConstraints'] = $this->prefixArrayKeys($table['uniqueConstraints']);
        }

        // add prefix to sequence
        if ($classMetadata->sequenceGeneratorDefinition) {
            $classMetadata->sequenceGeneratorDefinition['sequenceName'] =
                $this->prefix . $classMetadata->sequenceGeneratorDefinition['sequenceName'];
        }

        $classMetadata->table = $table;
    }

    protected function prefixArrayKeys(array $array)
    {
        $prefixedArray = array();

        foreach ($array as $key => $value) {
            $prefixedKey = $this->prefix . $key;
            if (isset($array[$prefixedKey])) {
                throw new Exception(sprintf('Prefixed name conflict %s', $prefixedKey));
            }
            $prefixedArray[$prefixedKey] = $value;
        }

        return $prefixedArray;
    }
}
