<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Specie;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class DeleteSpecieTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            AuthUserFixture::class,
            DeleteSpecieFixture::class,
        ]);
    }

    public function testEmptyId(): void
    {
        $response = $this->app()->handle(self::json('DELETE', '/v1/bank/species', authToken: $this->generateAuthToken()));

        self::assertEquals(405, $response->getStatusCode());
    }

    public function testNotExisting(): void
    {
        $response = $this->app()->handle(self::json('DELETE', '/v1/bank/species/6c8d5dfe-db99-4b48-becb-06cf2ca5e03a', authToken: $this->generateAuthToken()));

        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals(
            [
                'message' => 'Specie is not found.',
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testInvalidId(): void
    {
        $response = $this->app()->handle(self::json('DELETE', '/v1/bank/species/invalid', authToken: $this->generateAuthToken()));

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

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('DELETE', '/v1/bank/species/709e6e08-2edf-401f-bbe3-145398606f51', authToken: $this->generateAuthToken()));

        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('DELETE', '/v1/bank/species/709e6e08-2edf-401f-bbe3-145398606f51')
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
