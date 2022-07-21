<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\Tokenizer;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Auth\Service\Tokenizer
 *
 * @internal
 */
final class TokenizerTest extends TestCase
{
    public function testSuccess(): void
    {
        $interval = new DateInterval('PT1H');
        $now = new DateTimeImmutable();

        $tokenizer = new Tokenizer($interval);

        $token = $tokenizer->generate($now);

        self::assertEquals($now->add($interval), $token->getExpires());
    }
}
