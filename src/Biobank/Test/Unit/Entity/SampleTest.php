<?php

declare(strict_types=1);

namespace App\Biobank\Test\Unit\Entity;

use App\Biobank\Entity\Code;
use App\Biobank\Entity\Sample;
use App\Biobank\Entity\SampleData;
use App\Biobank\Entity\SampleTypeEnum;
use App\Biobank\Entity\Specie;
use App\Biobank\Entity\SpecieData;
use App\Biobank\Entity\SpecieName;
use App\Service\Id;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Biobank\Entity\Sample
 *
 * @internal
 */
final class SampleTest extends TestCase
{
    public function testCreate(): void
    {
        $data = $this->getSampleData();

        $sample = Sample::create($data);

        self::assertTrue($sample->getId()->equals($data->id));
        self::assertEquals($sample->getType(), $data->type);
        self::assertTrue($sample->getSpecie()->hasNameLat($data->specie->getNameLat()));
        self::assertTrue($sample->getCode()->equals($data->code));
        self::assertEquals($sample->getDate(), $data->date);
        self::assertEquals($sample->getPlace(), $data->place);
        self::assertEquals($sample->getMaterial(), $data->material);
        self::assertEquals($sample->getLat(), $data->lat);
        self::assertEquals($sample->getLon(), $data->lon);
        self::assertEquals($sample->getSex(), $data->sex);
        self::assertEquals($sample->getAge(), $data->age);
        self::assertEquals($sample->getResponsible(), $data->responsible);
        self::assertEquals($sample->getDescription(), $data->description);
        self::assertEquals($sample->getCs(), $data->cs);
        self::assertEquals($sample->getSr(), $data->sr);
        self::assertEquals($sample->getWaterbody(), $data->waterbody);
        self::assertEquals($sample->getCompany(), $data->company);
        self::assertEquals($sample->getDnaCode(), $data->dnaCode);
        self::assertEquals($sample->getInteriorCode(), $data->interiorCode);
        self::assertEquals($sample->getRingNumber(), $data->ringNumber);
    }

    public function testUpdate(): void
    {
        $data = $this->getSampleData();

        $sample = Sample::create($data);

        $newData = clone $data;
        $newData->lon = null;
        $newData->lat = null;
        $newData->date = null;
        $newData->responsible = 'Скворцов';

        $sample->update($newData);

        self::assertTrue($sample->getId()->equals($newData->id));
        self::assertEquals($sample->getType(), $newData->type);
        self::assertTrue($sample->getSpecie()->hasNameLat($newData->specie->getNameLat()));
        self::assertTrue($sample->getCode()->equals($newData->code));
        self::assertEquals($sample->getDate(), $newData->date);
        self::assertEquals($sample->getPlace(), $newData->place);
        self::assertEquals($sample->getMaterial(), $newData->material);
        self::assertEquals($sample->getLat(), $newData->lat);
        self::assertEquals($sample->getLon(), $newData->lon);
        self::assertEquals($sample->getSex(), $newData->sex);
        self::assertEquals($sample->getAge(), $newData->age);
        self::assertEquals($sample->getResponsible(), $newData->responsible);
        self::assertEquals($sample->getDescription(), $newData->description);
        self::assertEquals($sample->getCs(), $newData->cs);
        self::assertEquals($sample->getSr(), $newData->sr);
        self::assertEquals($sample->getWaterbody(), $newData->waterbody);
        self::assertEquals($sample->getCompany(), $newData->company);
        self::assertEquals($sample->getDnaCode(), $newData->dnaCode);
        self::assertEquals($sample->getInteriorCode(), $newData->interiorCode);
        self::assertEquals($sample->getRingNumber(), $newData->ringNumber);
    }

    private function getSampleData(): SampleData
    {
        $specie = new Specie(new SpecieData(Id::generate(), new SpecieName('Hirundo rustica')));

        return new SampleData(
            Id::generate(),
            SampleTypeEnum::BIRD,
            $specie,
            new Code('sample7'),
            '31.10.2016',
            'Витебская обл., Ушачский р-н, оз. Черствятское',
            'ткань в спирте',
            55.200611,
            29.806924,
            'f',
            'ad',
            'Журавлев',
            'кровь на фильтре',
            'A342WA0',
            'RING5432',
            '7±3',
            '-',
            'Krasnoslobodsky reservoir',
            'PSRER',
            'AE4353B45C2DEFF',
        );
    }
}
