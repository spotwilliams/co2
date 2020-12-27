<?php

namespace Co2Control\Services;

use Co2Control\Contracts\MeasurementPayload;
use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Repositories\MeasurementRepository;

class ControlC02Level
{
    /**
     * @var MeasurementRepository
     */
    private MeasurementRepository $measurementRepository;

    public function __construct(MeasurementRepository $measurementRepository)
    {
        $this->measurementRepository = $measurementRepository;
    }

    public function execute(Sensor $sensor): Sensor
    {
        return $sensor;
    }
}
