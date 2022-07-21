<?php

declare(strict_types=1);

namespace App\Biobank\Command\UpdateSpecie;

use App\Biobank\Entity\SpecieData;
use App\Biobank\Entity\SpecieName;
use App\Biobank\Entity\SpecieRepository;
use App\Biobank\Events\SpecieUpdated;
use App\Event\EventDispatcher;
use App\Service\Flusher;
use App\Service\Id;
use DomainException;

final class Handler
{
    public function __construct(
        private SpecieRepository $species,
        private EventDispatcher $eventDispatcher,
        private Flusher $flusher
    ) {
    }

    public function handle(Command $command): void
    {
        $specie = $this->species->get($command->id);

        $newLatName = new SpecieName($command->nameLat);

        if (!$specie->hasNameLat($newLatName) && $this->species->hasByLatName($newLatName)) {
            // e.g. user decided to fix a typo in nameLat
            throw new DomainException('Specie name already exists.');
        }

        $data = new SpecieData(
            new Id($command->id),
            new SpecieName($command->nameLat),
            new SpecieName($command->nameEn),
            new SpecieName($command->nameRu),
            new SpecieName($command->family),
            new SpecieName($command->order),
        );

        $specie->update($data);

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new SpecieUpdated($data));
    }
}
