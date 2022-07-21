<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Samples;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class DeleteSamplesTest extends WebTestCase
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
            self::json(
                'POST',
                '/v1/bank/samples/delete?type=psrer',
                ['ids' => ['invalid']],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals(
            [
                'errors' => [
                    'ids[0]' => 'This is not a valid UUID.',
                ],
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testNonExistentSample(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/bank/samples/delete?type=psrer',
                ['ids' => ['709e6e08-2edf-401f-bbe3-145398606f59']],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testEmptyType(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/bank/samples/delete',
                ['ids' => ['709e6e08-2edf-401f-bbe3-145398606f59']],
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

    public function testInvalidType(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/bank/samples/delete?type=invalid',
                ['ids' => ['709e6e08-2edf-401f-bbe3-145398606f59']],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/bank/samples/delete?type=psrer',
                ['ids' => ['709e6e08-2edf-401f-bbe3-145398606f52', '709e6e08-2edf-401f-bbe3-145398606f53']],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/v1/bank/samples/delete?type=psrer', [])
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
