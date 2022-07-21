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

final class MammalFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist($specie1 = new Specie(new SpecieData(Id::generate(), new SpecieName('Bison bonasus'))));
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::MAMMAL,
                    $specie1,
                    new Code('sample1'),
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::MAMMAL,
                    $specie1,
                    new Code('sample2'),
                    date: '31.10.2016',
                    place: 'ГПУ "НП "Беловежская пуща", Сухопольское л-во',
                    material: 'ткань в спирте',
                    lat: 51.601432,
                    lon: 29.806924,
                    sex: 'm',
                    age: '6-7 лет',
                    responsible: 'Юрченко',
                    description: 'Балонопостит, отстрел',
                    company: 'NAN',
                )
            )
        );
        $manager->flush();
    }
}
