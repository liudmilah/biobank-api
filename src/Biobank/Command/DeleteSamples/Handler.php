<?php

declare(strict_types=1);

namespace App\Biobank\Command\DeleteSamples;

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
        $this->samples->deleteByIds($command->ids, $command->type);

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new SampleDeleted($command->ids, $command->type));
    }
}
