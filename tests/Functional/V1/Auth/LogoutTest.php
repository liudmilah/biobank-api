<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use Dflydev\FigCookies\FigResponseCookies;
use Test\Functional\AuthUserFixture;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class LogoutTest extends WebTestCase
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
        $response = $this->app()->handle(self::json('GET', '/v1/auth/logout'));

        self::assertEquals(401, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/auth/logout', [], $this->generateAuthToken())
        );

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
        self::assertEmpty(FigResponseCookies::get($response, 'access_token')->getValue());
        self::assertEmpty(FigResponseCookies::get($response, 'refresh_token')->getValue());
    }
}
