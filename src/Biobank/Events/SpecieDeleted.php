<?php

declare(strict_types=1);

namespace App\Biobank\Events;

final class SpecieDeleted implements \App\Event\EventInterface
{
    public function __construct(public string $id)
    {
    }

    public function getName(): string
    {
        return 'SPECIE_DELETED';
    }
}
