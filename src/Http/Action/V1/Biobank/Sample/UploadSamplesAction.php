<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Biobank\Sample;

use App\Biobank\Command\ParseUploadSamplesFile\Command as ParseUploadSamplesFileCommand;
use App\Biobank\Command\ParseUploadSamplesFile\Handler as ParseUploadSamplesFileHandler;
use App\Biobank\Command\UploadSamples\Command as UploadSamplesCommand;
use App\Biobank\Command\UploadSamples\Handler as UploadSamplesHandler;
use App\Http\Response\EmptyResponse;
use App\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\UploadedFile;

final class UploadSamplesAction implements RequestHandlerInterface
{
    public function __construct(
        private Validator $validator,
        private UploadSamplesHandler $uploadSamplesHandler,
        private ParseUploadSamplesFileHandler $parseUploadSamplesFileHandler
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $files = $request->getUploadedFiles();

        /** @var ?UploadedFile $file */
        $file = $files['file'] ?? null;

        $parseUploadSamplesFileCommand = new ParseUploadSamplesFileCommand();
        /** @psalm-suppress InternalMethod */
        $parseUploadSamplesFileCommand->file = $file?->getFilePath() ?: '';
        $parseUploadSamplesFileCommand->type = (string)($request->getParsedBody()['type'] ?? '');
        $this->validator->validate($parseUploadSamplesFileCommand);
        $data = $this->parseUploadSamplesFileHandler->handle($parseUploadSamplesFileCommand);

        $uploadSamplesCommand = new UploadSamplesCommand();
        $uploadSamplesCommand->species = $data['species'];
        $uploadSamplesCommand->samples = $data['samples'];
        $uploadSamplesCommand->type = $data['type'];
        $this->validator->validate($uploadSamplesCommand);
        $this->uploadSamplesHandler->handle($uploadSamplesCommand);

        return new EmptyResponse(201);
    }
}
