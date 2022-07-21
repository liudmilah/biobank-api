<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class ChangePasswordTest extends WebTestCase
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
        $response = $this->app()->handle(self::json('PATCH', '/v1/auth/user/password'));

        self::assertEquals(401, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PATCH',
                '/v1/auth/user/password',
                [
                    'oldPassword' => 'password',
                    'newPassword' => 'Password',
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(204, $response->getStatusCode());
    }

    public function testInvalidOldPassword(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PATCH',
                '/v1/auth/user/password',
                [
                    'oldPassword' => 'InvalidOld',
                    'newPassword' => 'Password',
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals([
            'message' => 'Invalid old password.',
        ], Json::decode((string)$response->getBody()));
    }

    public function testLongPassword(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PATCH',
                '/v1/auth/user/password',
                [
                    'oldPassword' => str_repeat('*', 256),
                    'newPassword' => str_repeat('*', 256),
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'oldPassword' => 'This value is too long. It should have 255 characters or less.',
                'newPassword' => 'This value is too long. It should have 255 characters or less.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testShortPassword(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PATCH',
                '/v1/auth/user/password',
                [
                    'oldPassword' => 'P',
                    'newPassword' => 'P',
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'oldPassword' => 'This value is too short. It should have 8 characters or more.',
                'newPassword' => 'This value is too short. It should have 8 characters or more.',
            ],
        ], Json::decode((string)$response->getBody()));
    }
}
