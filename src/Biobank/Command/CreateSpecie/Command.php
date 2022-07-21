<?php

declare(strict_types=1);

namespace App\Biobank\Command\CreateSpecie;

use App\Biobank\Entity\Specie;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank, Assert\Length(max: Specie::NAME_LAT_MAX)]
    public ?string $nameLat = null;
    #[Assert\Length(max: Specie::NAME_EN_MAX)]
    public ?string $nameEn = null;
    #[Assert\Length(max: Specie::NAME_RU_MAX)]
    public ?string $nameRu = null;
    #[Assert\Length(max: Specie::FAMILY_MAX)]
    public ?string $family = null;
    #[Assert\Length(max: Specie::ORDER_MAX)]
    public ?string $order = null;
}
