<?php

namespace App\Infrastructure\Doctrine\Repositories;

use Co2Control\Entities\Sensor;
use Co2Control\Repositories\SensorRepository;

class DoctrineSensorRepository extends DoctrineReadRepository implements SensorRepository
{
    public function getEntity()
    {
        return Sensor::class;
    }
}
