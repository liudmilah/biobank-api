<?php

declare(strict_types=1);

use App\Biobank\Entity\Sample;
use App\Biobank\Entity\SampleRepository;
use App\Biobank\Entity\Specie;
use App\Biobank\Entity\SpecieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    SampleRepository::class => static function (ContainerInterface $container): SampleRepository {
        $em = $container->get(EntityManagerInterface::class);
        $repo = $em->getRepository(Sample::class);
        return new SampleRepository($em, $repo);
    },
    SpecieRepository::class => static function (ContainerInterface $container): SpecieRepository {
        $em = $container->get(EntityManagerInterface::class);
        $repo = $em->getRepository(Specie::class);
        return new SpecieRepository($em, $repo);
    },

    'config' => [
        'biobank' => [],
    ],
];
