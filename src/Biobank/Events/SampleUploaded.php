<?php

declare(strict_types=1);

namespace App\Biobank\Events;

use App\Biobank\Entity\SampleTypeEnum;

final class SampleUploaded implements \App\Event\EventInterface
{
    public function __construct(public SampleTypeEnum $type)
    {
    }

    public function getName(): string
    {
        return 'SAMPLE_UPLOADED';
    }
}
