<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use App\Service\Id;

final class SpecieData
{
    public function __construct(
        public Id $id,
        public SpecieName $nameLat,
        public ?SpecieName $nameEn = null,
        public ?SpecieName $nameRu = null,
        public ?SpecieName $family = null,
        public ?SpecieName $order = null,
    ) {
    }
}
