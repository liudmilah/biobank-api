<?php

declare(strict_types=1);

namespace App\Biobank\Test\Unit\Entity;

use App\Biobank\Entity\Specie;
use App\Biobank\Entity\SpecieData;
use App\Biobank\Entity\SpecieName;
use App\Service\Id;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Biobank\Entity\Specie
 *
 * @internal
 */
final class SpecieTest extends TestCase
{
    public function testCreate(): void
    {
        $data = $this->getSpecieData();

        $specie = Specie::create($data);

        self::assertEquals($specie->getNameLat(), $data->nameLat);
        self::assertEquals($specie->getNameEn(), $data->nameEn);
        self::assertEquals($specie->getNameRu(), $data->nameRu);
        self::assertEquals($specie->getFamily(), $data->family);
        self::assertEquals($specie->getOrder(), $data->order);
    }

    public function testUpdate(): void
    {
        $data = $this->getSpecieData();

        $specie = Specie::create($data);

        $newData = clone $data;
        $newData->nameEn = new SpecieName(null);
        $newData->nameRu = new SpecieName(null);
        $newData->family = new SpecieName(null);

        $specie->update($newData);

        self::assertEquals($specie->getNameLat(), $newData->nameLat);
        self::assertEquals($specie->getNameEn(), $newData->nameEn);
        self::assertEquals($specie->getNameRu(), $newData->nameRu);
        self::assertEquals($specie->getFamily(), $newData->family);
        self::assertEquals($specie->getOrder(), $newData->order);
    }

    public function testHasNameLat(): void
    {
        $data = $this->getSpecieData();

        $specie = new Specie($data);

        self::assertTrue($specie->hasNameLat($data->nameLat));
        self::assertFalse($specie->hasNameLat(new SpecieName('Apus apus')));
    }

    private function getSpecieData(): SpecieData
    {
        return new SpecieData(
            Id::generate(),
            new SpecieName('Hirundo rustica'),
            new SpecieName('Barn swallow'),
            new SpecieName('Деревенская ласточка'),
            new SpecieName('Hirundinidae'),
            new SpecieName('Passeriformes'),
        );
    }
}
