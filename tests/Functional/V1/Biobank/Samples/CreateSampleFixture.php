<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Samples;

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

final class CreateSampleFixture extends AbstractFixture
{
    public const CODE = 'A01283747';
    public const SPECIE_ID = '709e6e08-2edf-401f-bbe3-145398606f51';

    public function load(ObjectManager $manager): void
    {
        $specie = new Specie(new SpecieData(new Id(self::SPECIE_ID), new SpecieName('Hirundo rustica')));

        $sample = new Sample(new SampleData(
            Id::generate(),
            SampleTypeEnum::BIRD,
            $specie,
            new Code(self::CODE)
        ));

        $manager->persist($specie);
        $manager->persist($sample);

        $manager->flush();
    }
}
