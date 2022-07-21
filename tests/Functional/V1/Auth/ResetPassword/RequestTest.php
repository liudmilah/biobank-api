<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\ResetPassword;

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

        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password', [
            'email' => 'mail@example.com',
        ]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());

        self::assertTrue($this->mailer()->hasEmailSentTo('mail@example.com'));
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password', []));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'email' => 'This value should not be blank.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testNotValid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password', [
            'email' => 'invalid',
        ]));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'email' => 'This value is not a valid email address.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testNonExistent(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/reset-password', [
            'email' => 'unknown@example.com',
        ]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
        self::assertFalse($this->mailer()->hasEmailSentTo('unknown@example.com'));
    }
}
