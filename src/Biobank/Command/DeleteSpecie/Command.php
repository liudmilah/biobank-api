<?php

declare(strict_types=1);

namespace App\Biobank\Command\DeleteSpecie;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\Uuid, Assert\NotBlank]
    public string $id = '';
}
