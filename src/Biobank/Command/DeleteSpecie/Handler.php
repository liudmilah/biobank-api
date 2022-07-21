<?php

declare(strict_types=1);

namespace App\Biobank\Command\DeleteSpecie;

use App\Biobank\Entity\SpecieRepository;
use App\Biobank\Events\SpecieDeleted;
use App\Event\EventDispatcher;
use App\Service\Flusher;

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

        $this->species->remove($specie);

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new SpecieDeleted($command->id));
    }
}
