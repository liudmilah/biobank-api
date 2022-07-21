<?php

declare(strict_types=1);

namespace App\Biobank\Command\UploadSamples;

use App\Biobank\Entity\Sample;
use App\Biobank\Entity\Specie;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank]
    #[Assert\All([
        new Assert\Collection(
            fields: [
                'nameLat' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max: Specie::NAME_LAT_MAX),
                ],
                'nameEn' => new Assert\Length(max: Specie::NAME_EN_MAX),
                'nameRu' => new Assert\Length(max: Specie::NAME_RU_MAX),
                'family' => new Assert\Length(max: Specie::FAMILY_MAX),
                'order' => new Assert\Length(max: Specie::ORDER_MAX),
            ],
            allowMissingFields: false,
            allowExtraFields: true,
        ),
    ])]
    public array $species = [];

    #[Assert\NotBlank]
    #[Assert\All([
        new Assert\Collection(
            fields: [
                'code' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max: Sample::CODE_MAX),
                ],
                'specieName' => new Assert\Length(max: Specie::NAME_LAT_MAX),
                'interiorCode' => new Assert\Length(max: Sample::INT_CODE_MAX),
                'lat' => new Assert\Range(min: Sample::LAT_MIN, max: Sample::LAT_MAX),
                'lon' => new Assert\Range(min: Sample::LON_MIN, max: Sample::LON_MAX),
                'date' => new Assert\Length(max: Sample::DATE_MAX),
                'place' => new Assert\Length(max: Sample::PLACE_MAX),
                'material' => new Assert\Length(max: Sample::MATERIAL_MAX),
                'company' => new Assert\Length(max: Sample::COMPANY_MAX),
                'ringNumber' => new Assert\Length(max: Sample::RING_NUM_MAX),
                'sex' => new Assert\Length(max: Sample::SEX_MAX),
                'age' => new Assert\Length(max: Sample::AGE_MAX),
                'responsible' => new Assert\Length(max: Sample::RESPONSIBLE_MAX),
                'description' => new Assert\Length(max: Sample::DESCRIPTION_MAX),
                'cs' => new Assert\Length(max: Sample::CS_MAX),
                'sr' => new Assert\Length(max: Sample::SR_MAX),
                'waterbody' => new Assert\Length(max: Sample::WATERBODY_MAX),
                'dnaCode' => new Assert\Length(max: Sample::DNA_CODE_MAX),
            ],
            allowMissingFields: false,
            allowExtraFields: true,
        ),
    ])]
    public array $samples = [];
    #[Assert\NotBlank]
    public string $type = '';
}
