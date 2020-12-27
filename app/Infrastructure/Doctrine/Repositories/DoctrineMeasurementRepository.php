<?php

namespace App\Infrastructure\Doctrine\Repositories;

use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Repositories\MeasurementRepository;

class DoctrineMeasurementRepository extends DoctrineReadRepository implements MeasurementRepository
{
    public function getEntity()
    {
        return Measurement::class;
    }

    public function getLastMeasurements(Sensor $sensor, int $howMany): array
    {
        return $this->findBy(['sensor' => $sensor], ['createdAt' => 'DESC'], $howMany);
    }
}
