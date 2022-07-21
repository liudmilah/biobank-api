<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Confirm;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank]
    public string $token = '';
    #[Assert\NotBlank, Assert\Length(min: 8, max: 255)]
    public string $password = '';
}
