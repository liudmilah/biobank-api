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

final class AquaticInvertebrateFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist($specie1 = new Specie(
            new SpecieData(Id::generate(), new SpecieName('Dytiscus marginalis'))
        ));

        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::AI,
                    $specie1,
                    new Code('sample1'),
                    date: '06.08.2020',
                    place: 'Minsk',
                )
            )
        );

        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::AI,
                    $specie1,
                    new Code('sample2'),
                    date: '06.08.2020',
                    place: 'Minsk',
                    material: 'ткань в спирте',
                    lat: 51.601432,
                    lon: 29.806924,
                    waterbody: 'Krasnoslobodsky reservoir',
                )
            )
        );

        $manager->flush();
    }
}
