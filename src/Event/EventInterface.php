<?php

declare(strict_types=1);

namespace App\Event;

interface EventInterface
{
    public function getName(): string;
}
