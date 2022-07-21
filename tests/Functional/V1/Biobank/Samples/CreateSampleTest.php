<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Samples;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class CreateSampleTest extends WebTestCase
{
    private const URL = '/v1/bank/samples';

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
            CreateSampleFixture::class,
        ]);
    }

    public function testEmptyType(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                self::URL,
                [
                    'specieId' => CreateSampleFixture::SPECIE_ID,
                    'code' => '0000001',
                ],
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
                self::URL,
                [
                    'specieId' => CreateSampleFixture::SPECIE_ID,
                    'code' => '0000001',
                    'type' => 'invalid',
                ],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals(
            [
                'message' => 'Invalid sample type.',
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testEmptySpecie(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                self::URL,
                [
                    'code' => '0000001',
                    'type' => 'bird',
                ],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals(
            [
                'errors' => [
                    'specieId' => 'This value should not be blank.',
                ],
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testNonExistentSpecie(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                self::URL,
                [
                    'specieId' => '123e4567-e89b-12d3-a456-426614174000',
                    'code' => '0000001',
                    'type' => 'bird',
                ],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals(
            [
                'message' => 'Specie is not found.',
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testEmptyCode(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                self::URL,
                [
                    'specieId' => CreateSampleFixture::SPECIE_ID,
                    'type' => 'bird',
                ],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals(
            [
                'errors' => [
                    'code' => 'This value should not be blank.',
                ],
            ],
            Json::decode((string)$response->getBody())
        );
    }

    public function testExistingCode(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                self::URL,
                [
                    'specieId' => CreateSampleFixture::SPECIE_ID,
                    'type' => 'bird',
                    'code' => CreateSampleFixture::CODE,
                ],
                $this->generateAuthToken()
            )
        );
        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals(
            [
                'message' => 'Sample already exists.',
            ],
            Json::decode((string)$response->getBody())
        );
    }

    public function testInvalidFields(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                self::URL,
                [
                    'specieId' => '00000000',
                    'type' => str_repeat('*', 21),
                    'code' => str_repeat('*', 101),
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
                    'code' => 'This value is too long. It should have 100 characters or less.',
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
                'POST',
                self::URL,
                [
                    'specieId' => CreateSampleFixture::SPECIE_ID,
                    'code' => '0000001',
                    'type' => 'bird',
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
        self::assertEquals(200, $response->getStatusCode());
        self::assertNotEmpty(Json::decode((string)$response->getBody())['id']);
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('POST', self::URL, [])
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
