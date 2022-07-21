<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class SpecieNameType extends StringType
{
    public const NAME = 'specie_name_type';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof SpecieName ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): SpecieName
    {
        return new SpecieName($value ? (string)$value : null);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
