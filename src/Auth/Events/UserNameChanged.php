<?php

declare(strict_types=1);

namespace App\Auth\Events;

use App\Service\Id;

final class UserNameChanged implements \App\Event\EventInterface
{
    public function __construct(public Id $id, public string $name)
    {
    }

    public function getName(): string
    {
        return 'USER_NAME_CHANGED';
    }
}
