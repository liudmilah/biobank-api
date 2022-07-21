<?php

declare(strict_types=1);

namespace App\Biobank\Entity;

use App\Service\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id as ORMId;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'species')]
class Specie
{
    public const NAME_LAT_MAX = 255;
    public const NAME_EN_MAX = 255;
    public const NAME_RU_MAX = 255;
    public const FAMILY_MAX = 255;
    public const ORDER_MAX = 255;

    #[ORMId]
    #[Column(type: 'bb_id')]
    private Id $id;
    #[Column(type: 'specie_name_type', unique: true)]
    private SpecieName $nameLat;
    #[Column(type: 'specie_name_type', nullable: true)]
    private SpecieName $nameEn;
    #[Column(type: 'specie_name_type', nullable: true)]
    private SpecieName $nameRu;
    #[Column(type: 'specie_name_type', nullable: true)]
    private SpecieName $family;
    #[Column(name: 'sp_order', type: 'specie_name_type', nullable: true)] // order is reserved word in sql
    private SpecieName $order;

    public function __construct(SpecieData $data)
    {
        $this->load($data);
    }

    public function __toString(): string
    {
        return json_encode([
            'id' => $this->id->getValue(),
            'nameLat' => $this->nameLat->getValue(),
            'nameEn' => $this->nameEn->getValue(),
            'nameRu' => $this->nameRu->getValue(),
            'order' => $this->order->getValue(),
            'family' => $this->family->getValue(),
        ]);
    }

    public static function create(SpecieData $data): self
    {
        return new self($data);
    }

    public function update(SpecieData $data): void
    {
        $this->load($data);
    }

    public function hasNameLat(SpecieName $nameLat): bool
    {
        return $this->nameLat->getValue() === $nameLat->getValue();
    }

    public function getNameLat(): SpecieName
    {
        return $this->nameLat;
    }

    public function getNameEn(): SpecieName
    {
        return $this->nameEn;
    }

    public function getNameRu(): SpecieName
    {
        return $this->nameRu;
    }

    public function getFamily(): SpecieName
    {
        return $this->family;
    }

    public function getOrder(): SpecieName
    {
        return $this->order;
    }

    private function load(SpecieData $data): void
    {
        $this->id = $data->id;
        $this->nameLat = $data->nameLat;
        $this->nameRu = $data->nameRu ?? new SpecieName(null);
        $this->nameEn = $data->nameEn ?? new SpecieName(null);
        $this->order = $data->order ?? new SpecieName(null);
        $this->family = $data->family ?? new SpecieName(null);
    }
}
