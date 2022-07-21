<?php

declare(strict_types=1);

namespace Test\Functional\V1\Biobank\Specie;

use App\Biobank\Entity\Specie;
use App\Biobank\Entity\SpecieData;
use App\Biobank\Entity\SpecieName;
use App\Service\Id;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class CreateSpecieFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(new Specie(new SpecieData(new Id('709e6e08-2edf-401f-bbe3-145398606f51'), new SpecieName('Felis catus'))));
        $manager->flush();
    }
}
