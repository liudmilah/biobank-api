<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use Webmozart\Assert\Assert;

final class Code
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        Assert::notEmpty($value);
        Assert::maxLength($value, 100);
        $this->value = mb_strtoupper($value);
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $code): bool
    {
        return $this->getValue() === $code->getValue();
    }
}
