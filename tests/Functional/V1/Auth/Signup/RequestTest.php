<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Signup;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class RequestTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            RequestFixture::class,
        ]);
    }

    public function testSuccess(): void
    {
        $this->mailer()->clear();

        $response = $this->app()->handle(self::json('POST', '/v1/auth/signup', [
            'email' => 'new-user@example.com',
            'password' => 'new-password',
            'name' => 'new-name',
        ]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());

        self::assertTrue($this->mailer()->hasEmailSentTo('new-user@example.com'));
    }

    public function testExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/signup', [
            'email' => 'existing@example.com',
            'password' => 'new-password',
            'name' => 'new-name',
        ]));

        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals([
            'message' => 'User already exists.',
        ], Json::decode((string)$response->getBody()));
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/signup', []));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'email' => 'This value should not be blank.',
                'password' => 'This value is too short. It should have 8 characters or more.',
                'name' => 'This value should not be blank.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testNotValid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/signup', [
            'email' => 'invalid',
            'password' => '',
            'name' => '',
        ]));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'email' => 'This value is not a valid email address.',
                'password' => 'This value is too short. It should have 8 characters or more.',
                'name' => 'This value should not be blank.',
            ],
        ], Json::decode((string)$response->getBody()));
    }
}
