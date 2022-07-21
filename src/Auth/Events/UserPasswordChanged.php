<?php

declare(strict_types=1);

namespace App\Auth\Events;

use App\Service\Id;

final class UserPasswordChanged implements \App\Event\EventInterface
{
    public function __construct(public Id $id)
    {
    }

    public function getName(): string
    {
        return 'USER_PASSWORD_CHANGED';
    }
}
