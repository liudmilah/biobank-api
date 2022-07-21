<?php

declare(strict_types=1);

namespace Test\Functional;

/**
 * @internal
 */
final class HomeTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/'));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{}', (string)$response->getBody());
    }
}
