<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Service\Id;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Service\Id
 *
 * @internal
 */
final class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = Uuid::uuid4()->toString());

        self::assertEquals($value, $id->getValue());
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();

        $id = new Id(mb_strtoupper($value));

        self::assertEquals($value, $id->getValue());
    }

    public function testGenerate(): void
    {
        $id = Id::generate();

        self::assertNotEmpty($id->getValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Id('12345');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Id('');
    }
}
