<?php

declare(strict_types=1);

namespace App\Auth\Command\Signup\Confirm;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank]
    public string $token = '';
}
