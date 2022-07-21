<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use App\Service\Id;

final class SampleData
{
    public function __construct(
        public Id $id,
        public SampleTypeEnum $type,
        public Specie $specie,
        public Code $code,
        public ?string $date = null,
        public ?string $place = null,
        public ?string $material = null,
        public ?float $lat = null,
        public ?float $lon = null,
        public ?string $sex = null,
        public ?string $age = null,
        public ?string $responsible = null,
        public ?string $description = null,
        public ?string $interiorCode = null,
        public ?string $ringNumber = null,
        public ?string $cs = null,
        public ?string $sr = null,
        public ?string $waterbody = null,
        public ?string $company = null,
        public ?string $dnaCode = null,
    ) {
    }
}
