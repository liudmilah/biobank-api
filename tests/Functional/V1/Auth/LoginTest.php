<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use Dflydev\FigCookies\FigResponseCookies;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class LoginTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            LoginFixture::class,
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/v1/auth/login', ['email' => 'mail@example.com', 'password' => 'password'])
        );

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
        self::assertNotEmpty(FigResponseCookies::get($response, 'access_token')->getValue());
        self::assertNotEmpty(FigResponseCookies::get($response, 'refresh_token')->getValue());
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/v1/auth/login', [])
        );

        self::assertEquals(422, $response->getStatusCode());
        self::assertEmpty(FigResponseCookies::get($response, 'access_token')->getValue());
        self::assertEquals([
            'errors' => [
                'email' => 'This value should not be blank.',
                'password' => 'This value is too short. It should have 8 characters or more.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testInvalidEmail(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/v1/auth/login', ['email' => 'invalid@example.com', 'password' => 'password'])
        );

        self::assertEquals(409, $response->getStatusCode());
        self::assertEmpty(FigResponseCookies::get($response, 'access_token')->getValue());
        self::assertEquals(['message' => 'Invalid email or password.'], Json::decode((string)$response->getBody()));
    }

    public function testInvalidPassword(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/v1/auth/login', ['email' => 'mail@example.com', 'password' => 'invalid-password'])
        );

        self::assertEquals(409, $response->getStatusCode());
        self::assertEmpty(FigResponseCookies::get($response, 'access_token')->getValue());
        self::assertEquals(['message' => 'Invalid email or password.'], Json::decode((string)$response->getBody()));
    }
}
