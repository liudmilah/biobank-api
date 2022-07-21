<?php

declare(strict_types=1);

namespace App\Biobank\Fixture;

use App\Biobank\Entity\Code;
use App\Biobank\Entity\Sample;
use App\Biobank\Entity\SampleData;
use App\Biobank\Entity\SampleTypeEnum;
use App\Biobank\Entity\Specie;
use App\Biobank\Entity\SpecieData;
use App\Biobank\Entity\SpecieName;
use App\Service\Id;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class BirdFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist($specie1 = new Specie(new SpecieData(Id::generate(), new SpecieName('Hirundo rustica'))));
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie1,
                    new Code('sample1'),
                    date: '31.10.2016',
                    place: 'Minsk',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie1,
                    new Code('sample2'),
                    date: '31.10.2016',
                    place: 'Minsk',
                )
            )
        );

        $manager->persist($specie2 = new Specie(new SpecieData(Id::generate(), new SpecieName('Garrulus glandarius'))));
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie2,
                    new Code('sample3'),
                    date: '31.10.2016',
                    place: 'Minsk',
                )
            )
        );

        $manager->persist($specie3 = new Specie(new SpecieData(Id::generate(), new SpecieName('Schoeniclus schoeniclus'))));
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample4'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample5'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample6'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample7'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample8'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample9'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample10'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample11'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample12'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample13'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 28.88,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample14'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.30,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample15'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample16'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample17'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample18'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 28.84,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample19'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 28.87,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample20'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    lat: 55.200611,
                    lon: 29.806924,
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::BIRD,
                    $specie3,
                    new Code('sample21'),
                    date: '31.10.2016',
                    place: 'Витебская обл., Ушачский р-н, оз. Черствятское',
                    material: 'ткань в спирте',
                    sex: 'f',
                    age: 'ad',
                    responsible: 'Журавлев',
                    description: 'кровь на фильтре',
                    interiorCode: 'A342WA0',
                    ringNumber: 'RING5432',
                )
            )
        );

        $manager->flush();
    }
}
