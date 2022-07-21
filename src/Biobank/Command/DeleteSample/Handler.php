<?php

declare(strict_types=1);

namespace App\Biobank\Command\DeleteSample;

use App\Biobank\Entity\SampleRepository;
use App\Biobank\Events\SampleDeleted;
use App\Event\EventDispatcher;
use App\Service\Flusher;

final class Handler
{
    public function __construct(private SampleRepository $samples, private EventDispatcher $eventDispatcher, private Flusher $flusher)
    {
    }

    public function handle(Command $command): void
    {
        $sample = $this->samples->get($command->id);

        $type = $sample->getType()->value;

        $this->samples->remove($sample);

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new SampleDeleted([$command->id], $type));
    }
}
