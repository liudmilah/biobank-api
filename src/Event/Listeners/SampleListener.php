<?php

declare(strict_types=1);

namespace App\Event\Listeners;

use App\Biobank\Entity\SampleData;
use App\Biobank\Entity\SpecieData;
use App\Biobank\Events\SampleCreated;
use App\Biobank\Events\SampleDeleted;
use App\Biobank\Events\SampleDeletedAll;
use App\Biobank\Events\SampleUpdated;
use App\Biobank\Events\SampleUploaded;
use App\Biobank\Events\SpecieCreated;
use App\Biobank\Events\SpecieDeleted;
use App\Biobank\Events\SpecieUpdated;
use App\Event\EventInterface;
use App\Service\Publisher;

final class SampleListener implements \App\Event\ListenerInterface
{
    public function __construct(private Publisher $publisher)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function listen(): array
    {
        return [
            SampleCreated::class,
            SampleUploaded::class,
            SampleDeleted::class,
            SampleDeletedAll::class,
            SampleUpdated::class,
            SpecieCreated::class,
            SpecieDeleted::class,
            SpecieUpdated::class,
        ];
    }

    /**
     * @param EventInterface $event
     */
    public function process(object $event): void
    {
        $payload = match ($event::class) {
            SampleCreated::class => $this->exportSample($event->sample),
            SampleUploaded::class => ['type' => $event->type->value],
            SampleDeleted::class => ['type' => $event->type, 'ids' => $event->ids],
            SampleDeletedAll::class => ['type' => $event->type],
            SampleUpdated::class => $this->exportSample($event->sample),
            SpecieCreated::class => $this->exportSpecie($event->specie),
            SpecieUpdated::class => $this->exportSpecie($event->specie),
            SpecieDeleted::class => ['id' => $event->id],
            default => [],
        };

        $this->publisher->publish('samples', [
            'event' => $event->getName(),
            'payload' => $payload,
        ]);
    }

    private function exportSample(SampleData $data): array
    {
        $result = [];
        $exclude = ['id', 'code', 'specie', 'type'];
        foreach (get_object_vars($data) as $k => $v) {
            if (!\in_array($k, $exclude, true)) {
                $result[$k] = $v;
            }
        }

        $result['id'] = $data->id->getValue();
        $result['code'] = $data->code->getValue();
        $result['type'] = $data->type->value;
        $result['nameLat'] = $data->specie->getNameLat()->getValue();
        $result['nameRu'] = $data->specie->getNameRu()->getValue();
        $result['nameEn'] = $data->specie->getNameEn()->getValue();

        return array_filter($result, static fn ($item) => null !== $item);
    }

    private function exportSpecie(SpecieData $data): array
    {
        return [
            'id' => $data->id->getValue(),
            'nameLat' => $data->nameLat->getValue(),
            'nameEn' => $data->nameEn?->getValue(),
            'nameRu' => $data->nameRu?->getValue(),
            'family' => $data->family?->getValue(),
            'order' => $data->order?->getValue(),
        ];
    }
}
