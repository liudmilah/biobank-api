<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use App\Auth\Entity\User\Role;
use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class UserTest extends WebTestCase
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
        $response = $this->app()->handle(self::json('GET', '/v1/auth/user'));

        self::assertEquals(401, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/auth/user', authToken: $this->generateAuthToken())
        );

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'id' => AuthUserFixture::ID,
            'role' => Role::USER,
            'email' => AuthUserFixture::EMAIL,
            'name' => 'Test User',
        ], Json::decode($body));
    }
}
