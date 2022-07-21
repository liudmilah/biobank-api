<?php

declare(strict_types=1);

namespace App\Biobank\Command\UploadSamples;

use App\Biobank\Entity\Code;
use App\Biobank\Entity\Sample;
use App\Biobank\Entity\SampleData;
use App\Biobank\Entity\SampleRepository;
use App\Biobank\Entity\SampleTypeEnum;
use App\Biobank\Entity\Specie;
use App\Biobank\Entity\SpecieData;
use App\Biobank\Entity\SpecieName;
use App\Biobank\Entity\SpecieRepository;
use App\Biobank\Events\SampleUploaded;
use App\Event\EventDispatcher;
use App\Service\Flusher;
use App\Service\Id;
use DomainException;

final class Handler
{
    public function __construct(
        private Flusher $flusher,
        private EventDispatcher $eventDispatcher,
        private SampleRepository $samples,
        private SpecieRepository $species,
    ) {
    }

    /**
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function handle(Command $command): void
    {
        if (!$type = SampleTypeEnum::tryFrom($command->type)) {
            throw new DomainException('Invalid type');
        }

        $this->ensureUniqueSamplesCodes($command->samples, $command->type);

        $species = $this->getSpeciesEntities($command->species);

        $samples = $this->getSamplesEntities($command->samples, $species, $command->type);

        foreach ($species as $specie) {
            $this->species->add($specie);
        }

        foreach ($samples as $sample) {
            $this->samples->add($sample);
        }

        $this->flusher->flush();

        $this->eventDispatcher->dispatch(new SampleUploaded($type));
    }

    /**
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function ensureUniqueSamplesCodes(array $samples, string $type): void
    {
        $samplesCodes = array_map(
            static fn (string $code) => new Code($code),
            array_column($samples, 'code')
        );
        $existingCodes = $this->samples->getExistingCodes($type, $samplesCodes);
        if ($existingCodes) {
            throw new DomainException('The following codes already exist: ' . implode(',', $existingCodes));
        }
    }

    /**
     * @throws \Doctrine\ORM\Query\QueryException
     * @return Specie[]
     */
    private function getSpeciesEntities(array $species): array
    {
        $existingSpecies = $this->species->findAllByLatNames(
            array_map(
                static fn (string $name) => new SpecieName($name),
                array_column($species, 'nameLat')
            )
        );

        $result = [];
        /** @var array $specie */
        foreach ($species as $specie) {
            /** @psalm-suppress MixedArrayAccess, PossiblyNullArrayOffset */
            $nameLat = (new SpecieName((string)$specie['nameLat']))->getValue();
            /** @psalm-suppress MixedArrayAccess, PossiblyNullArrayOffset */
            $result[$nameLat] = $existingSpecies[$nameLat] ?? new Specie(
                new SpecieData(
                    Id::generate(),
                    new SpecieName((string)$specie['nameLat']),
                    new SpecieName((string)$specie['nameEn']),
                    new SpecieName((string)$specie['nameRu']),
                    new SpecieName((string)$specie['family']),
                    new SpecieName((string)$specie['order']),
                )
            );
        }

        return $result;
    }

    /**
     * @param Specie[] $species
     * @return Sample[]
     */
    private function getSamplesEntities(array $samples, array $species, string $type): array
    {
        $result = [];
        /** @var array $sample */
        foreach ($samples as $sample) {
            /** @psalm-suppress MixedArrayAccess, PossiblyNullArrayOffset */
            $specie = $species[(new SpecieName((string)$sample['specieName']))->getValue()];
            /** @psalm-suppress MixedArrayAccess */
            $data = new SampleData(
                Id::generate(),
                SampleTypeEnum::from($type),
                $specie,
                new Code((string)$sample['code']),
                (string)$sample['date'],
                (string)$sample['place'],
                (string)$sample['material'],
                isset($sample['lat']) ? (float) $sample['lat'] : null,
                isset($sample['lon']) ? (float) $sample['lon'] : null,
                (string)$sample['sex'],
                (string)$sample['age'],
                (string)$sample['responsible'],
                (string)$sample['description'],
                (string)$sample['interiorCode'],
                (string)$sample['ringNumber'],
                (string)$sample['company'],
                (string)$sample['cs'],
                (string)$sample['sr'],
                (string)$sample['waterbody'],
                (string)$sample['dnaCode'],
            );
            $result[] = new Sample($data);
        }

        return $result;
    }
}
