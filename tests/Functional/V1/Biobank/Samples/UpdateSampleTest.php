<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Samples;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class UpdateSampleTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
            UpdateSampleFixture::class,
        ]);
    }

    public function testInvalidId(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PUT',
                '/v1/bank/samples/invalid',
                [
                    'type' => 'psrer',
                ],
                $this->generateAuthToken()
            )
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
            self::json(
                'PUT',
                '/v1/bank/samples/709e6e08-2edf-401f-bbe3-145398606f59',
                [
                    'type' => 'psrer',
                ],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals(
            [
                'message' => 'Sample is not found.',
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testEmptyType(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PUT',
                '/v1/bank/samples/709e6e08-2edf-401f-bbe3-145398606f51',
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

    public function testInvalidType(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PUT',
                '/v1/bank/samples/709e6e08-2edf-401f-bbe3-145398606f51',
                [
                    'type' => 'invalid',
                ],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals(
            [
                'message' => 'Sample is not found.',
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testInvalidFields(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PUT',
                '/v1/bank/samples/709e6e08-2edf-401f-bbe3-145398606f51',
                [
                    'type' => str_repeat('*', 21),
                    'interiorCode' => str_repeat('*', 41),
                    'date' => str_repeat('*', 51),
                    'place' => str_repeat('*', 256),
                    'material' => str_repeat('*', 256),
                    'sex' => str_repeat('*', 21),
                    'age' => str_repeat('*', 21),
                    'responsible' => str_repeat('*', 101),
                    'description' => str_repeat('*', 256),
                    'ringNumber' => str_repeat('*', 41),
                    'cs' => str_repeat('*', 41),
                    'sr' => str_repeat('*', 41),
                    'waterbody' => str_repeat('*', 101),
                    'dnaCode' => str_repeat('*', 101),
                    'company' => str_repeat('*', 101),
                    'lat' => 95.0,
                    'lon' => 186.9,
                ],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(422, $response->getStatusCode());
        self::assertEquals(
            [
                'errors' => [
                    'type' => 'This value is too long. It should have 10 characters or less.',
                    'interiorCode' => 'This value is too long. It should have 40 characters or less.',
                    'date' => 'This value is too long. It should have 50 characters or less.',
                    'place' => 'This value is too long. It should have 255 characters or less.',
                    'material' => 'This value is too long. It should have 255 characters or less.',
                    'sex' => 'This value is too long. It should have 20 characters or less.',
                    'age' => 'This value is too long. It should have 20 characters or less.',
                    'responsible' => 'This value is too long. It should have 100 characters or less.',
                    'description' => 'This value is too long. It should have 255 characters or less.',
                    'ringNumber' => 'This value is too long. It should have 40 characters or less.',
                    'cs' => 'This value is too long. It should have 40 characters or less.',
                    'sr' => 'This value is too long. It should have 40 characters or less.',
                    'waterbody' => 'This value is too long. It should have 100 characters or less.',
                    'dnaCode' => 'This value is too long. It should have 100 characters or less.',
                    'company' => 'This value is too long. It should have 100 characters or less.',
                    'lat' => 'This value should be between -90 and 90.',
                    'lon' => 'This value should be between -180 and 180.',
                ],
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json(
                'PUT',
                '/v1/bank/samples/709e6e08-2edf-401f-bbe3-145398606f51',
                [
                    'type' => 'psrer',
                    'date' => '31.10.2016',
                    'place' => 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    'material' => 'ткань в спирте',
                    'lat' => 55.200611,
                    'lon' => 29.806924,
                    'sex' => 'f',
                    'age' => 'ad',
                    'responsible' => 'Журавлев',
                    'description' => 'кровь на фильтре',
                    'interiorCode' => 'A342WA0',
                    'ringNumber' => 'RING5432',
                    'company' => 'Company',
                ],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('PUT', '/v1/bank/samples/709e6e08-2edf-401f-bbe3-145398606f51')
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
