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

final class DeleteSampleFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist($specie1 = new Specie(new SpecieData(new Id('709e6e08-2edf-401f-bbe3-145398606f51'), new SpecieName('Felis catus'))));

        $manager->persist(new Sample(new SampleData(new Id('709e6e08-2edf-401f-bbe3-145398606f51'), SampleTypeEnum::PSRER, $specie1, new Code('psrer-code-1'))));
        $manager->persist(new Sample(new SampleData(new Id('709e6e08-2edf-401f-bbe3-145398606f52'), SampleTypeEnum::PSRER, $specie1, new Code('psrer-code-2'))));
        $manager->persist(new Sample(new SampleData(new Id('709e6e08-2edf-401f-bbe3-145398606f53'), SampleTypeEnum::PSRER, $specie1, new Code('psrer-code-3'))));

        $manager->flush();
    }
}
