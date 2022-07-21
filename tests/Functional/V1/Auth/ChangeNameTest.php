<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class ChangeNameTest extends WebTestCase
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
        $response = $this->app()->handle(self::json('PATCH', '/v1/auth/user/name'));

        self::assertEquals(401, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PATCH',
                '/v1/auth/user/name',
                [
                    'name' => 'Antonio Vivaldi',
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(204, $response->getStatusCode());
    }

    public function testLongName(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PATCH',
                '/v1/auth/user/name',
                [
                    'name' => str_repeat('*', 51),
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'name' => 'This value is too long. It should have 50 characters or less.',
            ],
        ], Json::decode((string)$response->getBody()));
    }
}
