<?php

namespace ManipleCore\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class Epoch extends Type
{
    const EPOCH = 'epoch';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return new \DateTime('@' . +$value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof \DateTime) {
            $value = $value->getTimestamp();
        }

        return +$value;
    }

    public function getName()
    {
        return self::EPOCH;
    }
}
