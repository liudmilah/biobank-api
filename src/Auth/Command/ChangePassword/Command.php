<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank, Assert\Length(min: 8, max: 255)]
    public string $oldPassword = '';
    #[Assert\NotBlank, Assert\Length(min: 8, max: 255)]
    public string $newPassword = '';
    #[Assert\NotBlank]
    public string $id = '';
}
