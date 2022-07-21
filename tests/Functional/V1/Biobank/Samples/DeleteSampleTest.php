<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Samples;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class DeleteSampleTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
            DeleteSampleFixture::class,
        ]);
    }

    public function testInvalidId(): void
    {
        $response = $this->app()->handle(
            self::json('DELETE', '/v1/bank/samples/invalid', authToken: $this->generateAuthToken())
        );
        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals(
            [
                'errors' => [
                    'id' => 'This is not a valid UUID.',
                ],
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testNonExistentSample(): void
    {
        $response = $this->app()->handle(
            self::json('DELETE', '/v1/bank/samples/123e4567-e89b-12d3-a456-426614174009', authToken: $this->generateAuthToken())
        );
        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals(
            [
                'message' => 'Sample is not found.',
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('DELETE', '/v1/bank/samples/709e6e08-2edf-401f-bbe3-145398606f51', authToken: $this->generateAuthToken())
        );
        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('DELETE', '/v1/bank/samples/709e6e08-2edf-401f-bbe3-145398606f51')
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
