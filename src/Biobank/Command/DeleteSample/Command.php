<?php

declare(strict_types=1);

namespace App\Biobank\Command\DeleteSample;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\Uuid, Assert\NotBlank]
    public string $id = '';
}
