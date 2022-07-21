<?php

declare(strict_types=1);

namespace App\Biobank\Command\CreateSpecie;

use App\Biobank\Entity\Specie;
use App\Biobank\Entity\SpecieData;
use App\Biobank\Entity\SpecieName;
use App\Biobank\Entity\SpecieRepository;
use App\Biobank\Events\SpecieCreated;
use App\Event\EventDispatcher;
use App\Service\Flusher;
use App\Service\Id;
use DomainException;

final class Handler
{
    public function __construct(private SpecieRepository $species, private EventDispatcher $eventDispatcher, private Flusher $flusher)
    {
    }

    public function handle(Command $command): void
    {
        $nameLat = new SpecieName($command->nameLat);
        if ($this->species->hasByLatName($nameLat)) {
            throw new DomainException('Specie already exists.');
        }

        $data = new SpecieData(
            Id::generate(),
            new SpecieName($command->nameLat),
            new SpecieName($command->nameEn),
            new SpecieName($command->nameRu),
            new SpecieName($command->family),
            new SpecieName($command->order),
        );

        $this->species->add(Specie::create($data));

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new SpecieCreated($data));
    }
}
