<?php

declare(strict_types=1);

namespace App\Biobank\Command\CreateSample;

use App\Biobank\Entity\Sample;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank, Assert\Length(max: Sample::CODE_MAX)]
    public string $code = '';
    #[Assert\NotBlank]
    public string $specieId = '';
    #[Assert\NotBlank, Assert\Length(max: Sample::TYPE_MAX)]
    public string $type = '';
    #[Assert\Length(max: Sample::INT_CODE_MAX)]
    public ?string $interiorCode = null;
    #[Assert\Range(min: Sample::LAT_MIN, max: Sample::LAT_MAX)]
    public ?float $lat = null;
    #[Assert\Range(min: Sample::LON_MIN, max: Sample::LON_MAX)]
    public ?float $lon = null;
    #[Assert\Length(max: Sample::DATE_MAX)]
    public ?string $date = null;
    #[Assert\Length(max: Sample::PLACE_MAX)]
    public ?string $place = null;
    #[Assert\Length(max: Sample::MATERIAL_MAX)]
    public ?string $material = null;
    #[Assert\Length(max: Sample::COMPANY_MAX)]
    public ?string $company = null;
    #[Assert\Length(max: Sample::RING_NUM_MAX)]
    public ?string $ringNumber = null;
    #[Assert\Length(max: Sample::SEX_MAX)]
    public ?string $sex = null;
    #[Assert\Length(max: Sample::AGE_MAX)]
    public ?string $age = null;
    #[Assert\Length(max: Sample::RESPONSIBLE_MAX)]
    public ?string $responsible = null;
    #[Assert\Length(max: Sample::DESCRIPTION_MAX)]
    public ?string $description = null;
    #[Assert\Length(max: Sample::CS_MAX)]
    public ?string $cs = null;
    #[Assert\Length(max: Sample::SR_MAX)]
    public ?string $sr = null;
    #[Assert\Length(max: Sample::WATERBODY_MAX)]
    public ?string $waterbody = null;
    #[Assert\Length(max: Sample::DNA_CODE_MAX)]
    public ?string $dnaCode = null;
}
