<?php

declare(strict_types=1);

namespace App\Biobank\Command\DeleteAll;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank]
    public string $type = '';
}
