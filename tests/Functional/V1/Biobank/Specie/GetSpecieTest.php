<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Specie;

use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class GetSpecieTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
            GetSpecieFixture::class,
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/bank/species', authToken: $this->generateAuthToken()));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals(
            [
                'species' => [
                    ['id' => '709e6e08-2edf-401f-bbe3-145398606f51', 'nameLat' => 'Felis catus'],
                ],
            ],
            Json::decode((string)$response->getBody()),
        );
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json('GET', '/v1/bank/species')
        );
        self::assertEquals(401, $response->getStatusCode());
    }
}
