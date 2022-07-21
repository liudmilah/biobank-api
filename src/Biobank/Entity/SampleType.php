<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class SampleType extends StringType
{
    public const NAME = 'sample_type';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof SampleTypeEnum ? $value->value : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?SampleTypeEnum
    {
        return !empty($value) ? SampleTypeEnum::from((string)$value) : null;
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
