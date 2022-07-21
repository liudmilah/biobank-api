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

final class GetSampleFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist($specie1 = new Specie(new SpecieData(new Id('709e6e08-2edf-401f-bbe3-145398606f51'), new SpecieName('Felis catus'))));
        $manager->persist($specie2 = new Specie(new SpecieData(new Id('709e6e08-2edf-401f-bbe3-145398606f52'), new SpecieName('Canus lupus'))));
        $manager->persist($specie3 = new Specie(new SpecieData(new Id('709e6e08-2edf-401f-bbe3-145398606f53'), new SpecieName('Apus apus'))));

        $manager->persist(new Sample(new SampleData(new Id('709e6e08-2edf-401f-bbe3-145398606f51'), SampleTypeEnum::PSRER, $specie1, new Code('psrer-code-1'))));
        $manager->persist(new Sample(new SampleData(new Id('709e6e08-2edf-401f-bbe3-145398606f52'), SampleTypeEnum::PSRER, $specie1, new Code('psrer-code-2'))));
        $manager->persist(new Sample(new SampleData(new Id('709e6e08-2edf-401f-bbe3-145398606f53'), SampleTypeEnum::PSRER, $specie2, new Code('psrer-code-3'))));

        $manager->persist(new Sample(new SampleData(new Id('709e6e08-2edf-401f-bbe3-145398606f54'), SampleTypeEnum::BIRD, $specie3, new Code('bird-code-4'))));

        $manager->flush();
    }
}