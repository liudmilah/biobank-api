<?php

declare(strict_types=1);

namespace App\Biobank\Events;

use App\Biobank\Entity\SampleData;

final class SampleUpdated implements \App\Event\EventInterface
{
    public function __construct(public SampleData $sample)
    {
    }

    public function getName(): string
    {
        return 'SAMPLE_UPDATED';
    }
}
