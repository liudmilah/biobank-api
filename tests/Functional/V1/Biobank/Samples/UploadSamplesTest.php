<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Samples;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\UploadedFile;
use Test\Functional\AuthUserFixture;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class UploadSamplesTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            AuthUserFixture::class,
            UploadSamplesFixture::class,
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUploads();
    }

    public function testSuccess(): void
    {
        $response = $this->sendRequest('Mammals_upload_short.xlsx');

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testEmptyFile(): void
    {
        $response = $this->sendRequest('Mammals_upload_empty_file.xlsx');

        self::assertEquals(409, $response->getStatusCode());
        self::assertEquals(['message' => 'Empty file.'], Json::decode((string)$response->getBody()));
    }

    public function testEmptyCode(): void
    {
        $response = $this->sendRequest('Mammals_upload_empty_code.xlsx');

        self::assertEquals(422, $response->getStatusCode());
        self::assertEquals(['errors' => [
            'samples[2][code]' => 'This value should not be blank.',
        ]], Json::decode((string)$response->getBody()));
    }

    public function testEmptyNameLat(): void
    {
        $response = $this->sendRequest('Mammals_upload_empty_name_lat.xlsx');

        self::assertEquals(422, $response->getStatusCode());
        self::assertEquals(['errors' => [
            'species[2][nameLat]' => 'This value should not be blank.',
        ]], Json::decode((string)$response->getBody()));
    }

    public function testInvalidColumn(): void
    {
        $response = $this->sendRequest('Mammals_upload_invalid_column.xlsx');

        self::assertEquals(409, $response->getStatusCode());
        self::assertEquals(
            ['message' => 'Please check your file, it contains unknown columns: invalid'],
            Json::decode((string)$response->getBody())
        );
    }

    public function testExistingSampleCode(): void
    {
        $response = $this->sendRequest('Mammals_upload_existing_code.xlsx');

        self::assertEquals(409, $response->getStatusCode());
        self::assertEquals(
            ['message' => 'The following codes already exist: TEST_CODE_1'],
            Json::decode((string)$response->getBody())
        );
    }

    public function testInvalidType(): void
    {
        $response = $this->sendRequest(type: 'invalid');

        self::assertEquals(409, $response->getStatusCode());
        self::assertEquals(
            ['message' => 'Invalid type'],
            Json::decode((string)$response->getBody())
        );
    }

    public function testEmptyType(): void
    {
        $response = $this->sendRequest(type: '');

        self::assertEquals(422, $response->getStatusCode());
        self::assertEquals(
            ['errors' => ['type' => 'This value should not be blank.']],
            Json::decode((string)$response->getBody())
        );
    }

    public function testInvalidFileType(): void
    {
        $response = $this->sendRequest('haus.png');

        self::assertEquals(422, $response->getStatusCode());
        self::assertEquals(
            ['errors' => ['file' => 'Please upload a valid xlsx file.']],
            Json::decode((string)$response->getBody())
        );
    }

    public function testNoFile(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/bank/samples/upload',
                [
                    'type' => 'psrer',
                ],
                $this->generateAuthToken()
            )
        );

        self::assertEquals(422, $response->getStatusCode());
        self::assertEquals(['errors' => ['file' => 'This value should not be blank.']], Json::decode((string)$response->getBody()));
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::file(
                'POST',
                '/v1/bank/samples/upload',
                [
                    'type' => 'psrer',
                ]
            )
        );
        self::assertEquals(401, $response->getStatusCode());
    }

    private function sendRequest(string $fileName = 'Mammals_upload_short.xlsx', string $type = 'psrer'): ResponseInterface
    {
        $this->uploadFile($fileName);

        return $this->app()->handle(
            self::file(
                'POST',
                '/v1/bank/samples/upload',
                [
                    'type' => $type,
                ],
                $this->generateAuthToken(),
                ['file' => new UploadedFile(self::UPLOADS_DIR . '/' . $fileName)]
            )
        );
    }
}
