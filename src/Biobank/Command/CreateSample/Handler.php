<?php

declare(strict_types=1);

namespace App\Biobank\Command\CreateSample;

use App\Biobank\Entity\Code;
use App\Biobank\Entity\Sample;
use App\Biobank\Entity\SampleData;
use App\Biobank\Entity\SampleRepository;
use App\Biobank\Entity\SampleTypeEnum;
use App\Biobank\Entity\SpecieRepository;
use App\Biobank\Events\SampleCreated;
use App\Event\EventDispatcher;
use App\Service\Flusher;
use App\Service\Id;
use DomainException;

final class Handler
{
    public function __construct(
        private SampleRepository $samples,
        private SpecieRepository $species,
        private Flusher $flusher,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function handle(Command $command): string
    {
        if (null === SampleTypeEnum::tryFrom($command->type)) {
            throw new DomainException('Invalid sample type.');
        }

        $code = new Code($command->code);

        if ($this->samples->hasByCode($command->type, $code)) {
            throw new DomainException('Sample already exists.');
        }

        $sampleData = $this->loadSampleData($command);

        $this->samples->add(Sample::create($sampleData));

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new SampleCreated($sampleData));

        return $sampleData->id->getValue();
    }

    private function loadSampleData(Command $command): SampleData
    {
        return new SampleData(
            Id::generate(),
            SampleTypeEnum::from($command->type),
            $this->species->get($command->specieId),
            new Code($command->code),
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
