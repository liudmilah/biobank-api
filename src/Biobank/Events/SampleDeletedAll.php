<?php

declare(strict_types=1);

namespace App\Biobank\Events;

final class SampleDeletedAll implements \App\Event\EventInterface
{
    public function __construct(public string $type)
    {
    }

    public function getName(): string
    {
        return 'SAMPLE_DELETED_ALL';
    }
}
