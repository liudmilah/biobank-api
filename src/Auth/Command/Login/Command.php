<?php

declare(strict_types=1);

namespace App\Auth\Command\Login;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank, Assert\Email]
    public string $email = '';
    #[Assert\NotBlank, Assert\Length(min: 8)]
    public string $password = '';
}
