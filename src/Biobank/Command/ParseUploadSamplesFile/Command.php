<?php

declare(strict_types=1);

namespace App\Biobank\Command\ParseUploadSamplesFile;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank, Assert\File(
        maxSize: '2M',
        mimeTypes: ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        mimeTypesMessage: 'Please upload a valid xlsx file.',
    )]
    public string $file = '';
    #[Assert\NotBlank]
    public string $type = '';
}
