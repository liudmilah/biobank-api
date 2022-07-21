<?php

declare(strict_types=1);

namespace App\Biobank\Command\DeleteAll;

use App\Biobank\Entity\SampleRepository;
use App\Biobank\Events\SampleDeletedAll;
use App\Event\EventDispatcher;
use App\Service\Flusher;

final class Handler
{
    public function __construct(private SampleRepository $samples, private EventDispatcher $eventDispatcher, private Flusher $flusher)
    {
    }

    public function handle(Command $command): void
    {
        $this->samples->deleteAll($command->type);

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new SampleDeletedAll($command->type));
    }
}
