<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use App\Service\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id as ORMId;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'samples')]
final class Sample
{
    public const CODE_MAX = 100;
    public const TYPE_MAX = 10;
    public const DATE_MAX = 50;
    public const PLACE_MAX = 255;
    public const MATERIAL_MAX = 255;
    public const LAT_MAX = 90;
    public const LAT_MIN = -90;
    public const LON_MAX = 180;
    public const LON_MIN = -180;
    public const SEX_MAX = 20;
    public const AGE_MAX = 20;
    public const RESPONSIBLE_MAX = 100;
    public const DESCRIPTION_MAX = 255;
    public const CS_MAX = 40;
    public const SR_MAX = 40;
    public const WATERBODY_MAX = 100;
    public const COMPANY_MAX = 100;
    public const DNA_CODE_MAX = 100;
    public const INT_CODE_MAX = 40;
    public const RING_NUM_MAX = 40;

    #[ORMId, Column(type: 'bb_id')]
    private Id $id;
    #[Column(type: 'sample_code')]
    private Code $code;
    #[
        ManyToOne(targetEntity: Specie::class),
        JoinColumn(name: 'specie_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')
    ]
    private Specie $specie;
    #[Column(type: 'sample_type')]
    private SampleTypeEnum $type;
    #[Column(type: 'string', length: self::DATE_MAX, nullable: true)]
    private ?string $date;
    #[Column(type: 'string', length: self::PLACE_MAX, nullable: true)]
    private ?string $place;
    #[Column(type: 'string', length: self::MATERIAL_MAX, nullable: true)]
    private ?string $material;
    #[Column(type: 'float', nullable: true)]
    private ?float $lat;
    #[Column(type: 'float', nullable: true)]
    private ?float $lon;
    #[Column(type: 'string', length: self::SEX_MAX, nullable: true)]
    private ?string $sex;
    #[Column(type: 'string', length: self::AGE_MAX, nullable: true)]
    private ?string $age;
    #[Column(type: 'string', length: self::RESPONSIBLE_MAX, nullable: true)]
    private ?string $responsible;
    #[Column(type: 'string', length: self::DESCRIPTION_MAX, nullable: true)]
    private ?string $description;
    #[Column(type: 'string', length: self::CS_MAX, nullable: true)]
    private ?string $cs;
    #[Column(type: 'string', length: self::SR_MAX, nullable: true)]
    private ?string $sr;
    #[Column(type: 'string', length: self::WATERBODY_MAX, nullable: true)]
    private ?string $waterbody;
    #[Column(type: 'string', length: self::COMPANY_MAX, nullable: true)]
    private ?string $company;
    #[Column(type: 'string', length: self::DNA_CODE_MAX, nullable: true)]
    private ?string $dnaCode;
    #[Column(type: 'string', length: self::INT_CODE_MAX, nullable: true)]
    private ?string $interiorCode;
    #[Column(type: 'string', length: self::RING_NUM_MAX, nullable: true)]
    private ?string $ringNumber;

    public function __construct(SampleData $data)
    {
        $this->load($data);
    }

    public function update(SampleData $data): void
    {
        $this->load($data);
    }

    public static function create(SampleData $data): self
    {
        return new self($data);
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getType(): SampleTypeEnum
    {
        return $this->type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function getResponsible(): ?string
    {
        return $this->responsible;
    }

    public function getCs(): ?string
    {
        return $this->cs;
    }

    public function getSr(): ?string
    {
        return $this->sr;
    }

    public function getWaterbody(): ?string
    {
        return $this->waterbody;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function getDnaCode(): ?string
    {
        return $this->dnaCode;
    }

    public function getInteriorCode(): ?string
    {
        return $this->interiorCode;
    }

    public function getRingNumber(): ?string
    {
        return $this->ringNumber;
    }

    public function getSpecie(): Specie
    {
        return $this->specie;
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    private function load(SampleData $data): void
    {
        $this->id = $data->id;
        $this->code = $data->code;
        $this->type = $data->type;
        $this->specie = $data->specie;
        $this->date = $data->date;
        $this->place = $data->place;
        $this->material = $data->material;
        $this->lat = $data->lat;
        $this->lon = $data->lon;
        $this->sex = $data->sex;
        $this->age = $data->age;
        $this->responsible = $data->responsible;
        $this->description = $data->description;
        $this->cs = $data->cs;
        $this->sr = $data->sr;
        $this->waterbody = $data->waterbody;
        $this->company = $data->company;
        $this->dnaCode = $data->dnaCode;
        $this->interiorCode = $data->interiorCode;
        $this->ringNumber = $data->ringNumber;
    }
}
