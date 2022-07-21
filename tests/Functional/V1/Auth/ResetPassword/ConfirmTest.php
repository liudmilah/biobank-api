<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\ResetPassword;

use Ramsey\Uuid\Uuid;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class ConfirmTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            ConfirmFixture::class,
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password/confirm', [
            'token' => ConfirmFixture::VALID,
            'password' => 'newPassword',
        ]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testExpiredToken(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password/confirm', [
            'token' => ConfirmFixture::EXPIRED,
            'password' => 'newPassword',
        ]));

        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals([
            'message' => 'Token is expired.',
        ], Json::decode((string)$response->getBody()));
    }

    public function testEmptyToken(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password/confirm', [
            'password' => 'newPassword',
        ]));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'token' => 'This value should not be blank.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testNotExistingToken(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password/confirm', [
            'token' => Uuid::uuid4()->toString(),
            'password' => 'newPassword',
        ]));

        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals([
            'message' => 'Token is not found.',
        ], Json::decode((string)$response->getBody()));
    }

    public function testEmptyPassword(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password/confirm', [
            'token' => Uuid::uuid4()->toString(),
        ]));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'password' => 'This value is too short. It should have 8 characters or more.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testShortPassword(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password/confirm', [
            'token' => Uuid::uuid4()->toString(),
            'password' => 'pass',
        ]));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'password' => 'This value is too short. It should have 8 characters or more.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testLongPassword(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password/confirm', [
            'token' => Uuid::uuid4()->toString(),
            'password' => str_repeat('*', 256),
        ]));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'password' => 'This value is too long. It should have 255 characters or less.',
            ],
        ], Json::decode((string)$response->getBody()));
    }
}
