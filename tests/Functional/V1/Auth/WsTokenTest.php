<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class WsTokenTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
        ]);
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/ws-token'));

        self::assertEquals(401, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/auth/ws-token', [], $this->generateAuthToken())
        );

        self::assertEquals(200, $response->getStatusCode());
        self::assertNotEmpty(Json::decode((string)$response->getBody())['token']);
    }
}
