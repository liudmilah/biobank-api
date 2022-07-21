<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Specie;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class CreateSpecieTest extends WebTestCase
{
    private const URL = '/v1/bank/species';

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
            CreateSpecieFixture::class,
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                self::URL,
                [
                    'nameLat' => 'new-lat',
                    'nameEn' => 'new-en',
                    'nameRu' => 'new-рус',
                    'order' => 'new-order',
                    'family' => 'new-family',
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testEmptyName(): void
    {
        $response = $this->app()->handle(self::json('POST', self::URL, [], $this->generateAuthToken()));

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals(
            [
                'errors' => [
                    'nameLat' => 'This value should not be blank.',
                ],
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testExistingName(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                self::URL,
                [
                    'nameLat' => 'Felis catus',
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(409, $response->getStatusCode());

        self::assertEquals(
            [
                'message' => 'Specie already exists.',
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testNotValid(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                self::URL,
                [
                    'nameLat' => str_repeat('*', 256),
                    'nameEn' => str_repeat('*', 256),
                    'nameRu' => str_repeat('*', 256),
                    'order' => str_repeat('*', 256),
                    'family' => str_repeat('*', 256),
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(422, $response->getStatusCode());

        self::assertEquals(
            [
                'errors' => [
                    'nameLat' => 'This value is too long. It should have 255 characters or less.',
                    'nameEn' => 'This value is too long. It should have 255 characters or less.',
                    'nameRu' => 'This value is too long. It should have 255 characters or less.',
                    'order' => 'This value is too long. It should have 255 characters or less.',
                    'family' => 'This value is too long. It should have 255 characters or less.',
                ],
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('POST', self::URL)
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
