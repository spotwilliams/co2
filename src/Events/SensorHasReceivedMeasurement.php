<?php

namespace Co2Control\Events;

use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Illuminate\Contracts\Queue\ShouldQueue;

class SensorHasReceivedMeasurement implements ShouldQueue
{
    private string $sensorId;
    private string $measurementId;

    public function __construct(Sensor $sensor, Measurement $measurement)
    {
        $this->sensorId = $sensor->getId();
        $this->measurementId = $measurement->getId();
    }

    public function getSensorId(): string
    {
        return $this->sensorId;
    }

    public function getMeasurementId(): string
    {
        return $this->measurementId;
    }
}
