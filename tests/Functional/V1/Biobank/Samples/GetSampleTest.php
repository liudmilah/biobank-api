<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Samples;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class GetSampleTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
            GetSampleFixture::class,
        ]);
    }

    public function testEmptyType(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples', authToken: $this->generateAuthToken()));
        self::assertEquals(422, $response->getStatusCode());
    }

    public function testInvalidType(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples?type=invalid', authToken: $this->generateAuthToken()));
        self::assertEquals(422, $response->getStatusCode());
    }

    public function testBird(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples?type=bird', authToken: $this->generateAuthToken()));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals([
            'list' => [
                [
                    'id' => '709e6e08-2edf-401f-bbe3-145398606f54',
                    'code' => 'BIRD-CODE-4',
                    'specieId' => '709e6e08-2edf-401f-bbe3-145398606f53',
                    'type' => 'bird',
                    'nameLat' => 'Apus apus',
                ],
            ],
            'amount' => 1,
        ], Json::decode((string)$response->getBody()));
    }

    public function testPsrer(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples?type=psrer', authToken: $this->generateAuthToken()));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals([
            'list' => [
                [
                    'id' => '709e6e08-2edf-401f-bbe3-145398606f51',
                    'code' => 'PSRER-CODE-1',
                    'specieId' => '709e6e08-2edf-401f-bbe3-145398606f51',
                    'type' => 'psrer',
                    'nameLat' => 'Felis catus',
                ],
                [
                    'id' => '709e6e08-2edf-401f-bbe3-145398606f52',
                    'code' => 'PSRER-CODE-2',
                    'specieId' => '709e6e08-2edf-401f-bbe3-145398606f51',
                    'type' => 'psrer',
                    'nameLat' => 'Felis catus',
                ],
                [
                    'id' => '709e6e08-2edf-401f-bbe3-145398606f53',
                    'code' => 'PSRER-CODE-3',
                    'specieId' => '709e6e08-2edf-401f-bbe3-145398606f52',
                    'type' => 'psrer',
                    'nameLat' => 'Canus lupus',
                ],
            ],
            'amount' => 3,
        ], Json::decode((string)$response->getBody()));
    }

    public function testPaginationAndSorting(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples?type=psrer&sort=-nameLat&limit=1', authToken: $this->generateAuthToken()));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals([
            'list' => [
                [
                    'id' => '709e6e08-2edf-401f-bbe3-145398606f51',
                    'code' => 'PSRER-CODE-1',
                    'specieId' => '709e6e08-2edf-401f-bbe3-145398606f51',
                    'type' => 'psrer',
                    'nameLat' => 'Felis catus',
                ],
            ],
            'amount' => 3,
        ], Json::decode((string)$response->getBody()));
    }

    public function testBigOffset(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples?type=psrer&offset=1000', authToken: $this->generateAuthToken()));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals([
            'list' => [],
            'amount' => 3,
        ], Json::decode((string)$response->getBody()));
    }

    public function testBigLimit(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples?type=psrer&limit=1000', authToken: $this->generateAuthToken()));

        self::assertEquals(422, $response->getStatusCode());
    }

    public function testInvalidOrderBy(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples?type=psrer&sort=invalid', authToken: $this->generateAuthToken()));

        self::assertEquals(422, $response->getStatusCode());
    }

    public function testInvalidOrder(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples?type=psrer&sort=code', authToken: $this->generateAuthToken()));

        self::assertEquals(422, $response->getStatusCode());
    }

    public function testSearch(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/samples?type=psrer&search=Canus', authToken: $this->generateAuthToken()));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals([
            'list' => [
                [
                    'id' => '709e6e08-2edf-401f-bbe3-145398606f53',
                    'code' => 'PSRER-CODE-3',
                    'specieId' => '709e6e08-2edf-401f-bbe3-145398606f52',
                    'type' => 'psrer',
                    'nameLat' => 'Canus lupus',
                ],
            ],
            'amount' => 1,
        ], Json::decode((string)$response->getBody()));
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/bank/samples?type=psrer')
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
