<?php

declare(strict_types=1);

namespace App\Biobank\Events;

final class SampleDeleted implements \App\Event\EventInterface
{
    public function __construct(public array $ids, public string $type)
    {
    }

    public function getName(): string
    {
        return 'SAMPLE_DELETED';
    }
}
