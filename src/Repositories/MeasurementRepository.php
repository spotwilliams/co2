<?php

namespace Co2Control\Repositories;

use Co2Control\Entities\Sensor;

interface MeasurementRepository extends ReadRepository
{
    public function getLastMeasurements(Sensor $sensor, int $howMany): array;
}
