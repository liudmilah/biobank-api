<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeName;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank, Assert\Length(max: 50)]
    public string $name = '';
    #[Assert\NotBlank]
    public string $id = '';
}
