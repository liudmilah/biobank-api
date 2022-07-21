<?php

declare(strict_types=1);

namespace App\Biobank\Events;

use App\Biobank\Entity\SpecieData;

final class SpecieCreated implements \App\Event\EventInterface
{
    public function __construct(public SpecieData $specie)
    {
    }

    public function getName(): string
    {
        return 'SPECIE_CREATED';
    }
}
