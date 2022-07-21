<?php

declare(strict_types=1);

namespace App\Auth\Command\Signup\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank, Assert\Email]
    public string $email = '';
    #[Assert\NotBlank, Assert\Length(min: 8, max: 255)]
    public string $password = '';
    #[Assert\NotBlank, Assert\Length(max: 50)]
    public string $name = '';
}
