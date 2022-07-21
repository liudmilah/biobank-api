<?php

declare(strict_types=1);

namespace App\Biobank\Test\Unit\Entity;

use App\Biobank\Entity\SpecieName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Biobank\Entity\SpecieName
 *
 * @internal
 */
final class SpecieNameTest extends TestCase
{
    public function testSuccess(): void
    {
        $name = new SpecieName($value = 'Barn swallow');

        self::assertEquals($value, $name->getValue());
    }

    public function testToString(): void
    {
        $name = new SpecieName($value = 'Barn swallow');

        self::assertEquals($value, $name->__toString());
    }

    public function testEmpty(): void
    {
        $name = new SpecieName('');

        self::assertNull($name->getValue());
    }

    public function testLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SpecieName(str_repeat('*', 256));
    }

    public function testCase(): void
    {
        $name = new SpecieName('BARN SWallow');

        self::assertEquals('Barn swallow', $name->getValue());
    }
}
