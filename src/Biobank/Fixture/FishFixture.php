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

final class FishFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist($specie1 = new Specie(new SpecieData(Id::generate(), new SpecieName('Esox lucius'))));
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::FISH,
                    $specie1,
                    new Code('sample1'),
                )
            )
        );
        $manager->persist(
            new Sample(
                new SampleData(
                    Id::generate(),
                    SampleTypeEnum::FISH,
                    $specie1,
                    new Code('sample2'),
                    date: '31.10.2016',
                    place: 'Minsk region Salihorsk district',
                    material: 'ткань в спирте',
                    lat: 51.601432,
                    lon: 29.806924,
                    responsible: 'Ризевский',
                    waterbody: 'Krasnoslobodsky reservoir',
                    dnaCode: 'AE4353B45C2DEFF',
                )
            )
        );

        $manager->flush();
    }
}
