<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Samples;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class GetStatisticsTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
            GetStatisticsFixture::class,
        ]);
    }

    public function testEmptyType(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples/statistics', authToken: $this->generateAuthToken()));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals([
            'errors' => [
                'type' => 'This value should not be blank.',
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testInvalidType(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples/statistics?type=invalid', authToken: $this->generateAuthToken()));

        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals([
            'message' => 'Invalid sample type.',
        ], Json::decode((string)$response->getBody()));
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples/statistics?type=psrer', authToken: $this->generateAuthToken()));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals([
            'statistics' => [
                ['name' => 'Felis catus', 'amount' => 2],
                ['name' => 'Canus lupus', 'amount' => 1],
            ],
        ], Json::decode((string)$response->getBody()));
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/bank/samples/statistics?type=psrer')
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
