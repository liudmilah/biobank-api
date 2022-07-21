<?php

declare(strict_types=1);

namespace App\Biobank\Command\DeleteSamples;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank]
    #[Assert\All([
        new Assert\NotBlank(),
        new Assert\Uuid(),
    ])]
    public array $ids = [];

    #[Assert\NotBlank]
    public string $type = '';
}
