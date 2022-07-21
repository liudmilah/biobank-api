<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

enum SampleTypeEnum: string
{
    case BIRD = 'bird';
    case PSRER = 'psrer';
    case AI = 'ai';
    case MAMMAL = 'mammal';
    case FISH = 'fish';
}
