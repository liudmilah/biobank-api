<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Samples;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class DeleteAllSamplesTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
            DeleteSampleFixture::class,
        ]);
    }

    public function testInvalidType(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/bank/samples/all?type=invalid',
                [],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());

        // make sure that other samples were not removed
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
                    'specieId' => '709e6e08-2edf-401f-bbe3-145398606f51',
                    'type' => 'psrer',
                    'nameLat' => 'Felis catus',
                ],
            ],
            'amount' => 3,
        ], Json::decode((string)$response->getBody()));
    }

    public function testEmptyType(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/bank/samples/all',
                [],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(422, $response->getStatusCode());
        self::assertEquals(
            [
                'errors' => [
                    'type' => 'This value should not be blank.',
                ],
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/bank/samples/all?type=psrer',
                [],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/v1/bank/samples/all?type=psrer', [])
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
