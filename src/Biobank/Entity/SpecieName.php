<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use Webmozart\Assert\Assert;

final class SpecieName
{
    private ?string $value;

    public function __construct(?string $value)
    {
        if ($value) {
            Assert::maxLength($value, 255);
        }

        $this->value = self::normalizeName($value);
    }

    public function __toString(): string
    {
        return $this->getValue() ?: '';
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function equals(?self $name): bool
    {
        return $name && $name->getValue() === $this->getValue();
    }

    /**
     * ucfirst for multibyte strings.
     */
    private static function normalizeName(?string $name): ?string
    {
        if (empty($name)) {
            return null;
        }

        $name = mb_strtolower($name);
        return mb_strtoupper(mb_substr($name, 0, 1))
            . mb_substr($name, 1);
    }
}
