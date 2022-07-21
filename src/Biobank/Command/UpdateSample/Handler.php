<?php

declare(strict_types=1);

namespace App\Biobank\Command\UpdateSample;

use App\Biobank\Entity\Sample;
use App\Biobank\Entity\SampleData;
use App\Biobank\Entity\SampleRepository;
use App\Biobank\Entity\SpecieRepository;
use App\Biobank\Events\SampleUpdated;
use App\Event\EventDispatcher;
use App\Service\Flusher;

final class Handler
{
    public function __construct(private SampleRepository $samples, private SpecieRepository $species, private EventDispatcher $eventDispatcher, private Flusher $flusher)
    {
    }

    public function handle(Command $command): void
    {
        $sample = $this->samples->get($command->id, $command->type);

        $sampleData = $this->loadSampleData($command, $sample);

        $sample->update($sampleData);

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new SampleUpdated($sampleData));
    }

    private function loadSampleData(Command $command, Sample $sample): SampleData
    {
        return new SampleData(
            $sample->getId(),
            $sample->getType(),
            $sample->getSpecie(),
            $sample->getCode(),
            $command->date,
            $command->place,
            $command->material,
            $command->lat,
            $command->lon,
            $command->sex,
            $command->age,
            $command->responsible,
            $command->description,
            $command->interiorCode,
            $command->ringNumber,
            $command->company,
            $command->cs,
            $command->sr,
            $command->waterbody,
            $command->dnaCode,
        );
    }
}
