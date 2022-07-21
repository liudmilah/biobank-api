<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Signup;

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
        $response = $this->app()->handle(self::json('POST', '/v1/auth/signup/confirm', [
            'token' => ConfirmFixture::VALID,
        ]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

//    public function testExpired(): void
//    {
//        $response = $this->app()->handle(self::json('POST', '/v1/auth/signup/confirm', [
//            'token' => ConfirmFixture::EXPIRED,
//        ]));
//
//        self::assertEquals(409, $response->getStatusCode());
//
//        self::assertEquals([
//            'message' => 'Token is expired.',
//        ], Json::decode((string)$response->getBody()));
//    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/signup/confirm', []));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'token' => 'This value should not be blank.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testNotExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/signup/confirm', [
            'token' => Uuid::uuid4()->toString(),
        ]));

        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals([
            'message' => 'Incorrect token.',
        ], Json::decode((string)$response->getBody()));
    }
}
