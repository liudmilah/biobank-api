<?php

declare(strict_types=1);

namespace App\Biobank\Test\Unit\Entity;

use App\Biobank\Entity\Code;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Biobank\Entity\Code
 *
 * @internal
 */
final class CodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $code = new Code($value = 'ABCDE');

        self::assertEquals($value, $code->getValue());
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Code('');
    }

    public function testLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Code(str_repeat('*', 101));
    }

    public function testCase(): void
    {
        $code = new Code($value = 'abcde');

        self::assertEquals(strtoupper($value), $code->getValue());
    }

    public function testToString(): void
    {
        $code = new Code($value = 'ABCDE');

        self::assertEquals($value, $code->__toString());
    }
}
