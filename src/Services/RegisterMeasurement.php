<?php

namespace Co2Control\Services;

use App\Jobs\ControlC02AfterMeasurementRegistration;
use Co2Control\Contracts\MeasurementPayload;
use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Repositories\MeasurementRepository;

class RegisterMeasurement
{
    /**
     * @var MeasurementRepository
     */
    private MeasurementRepository $measurementRepository;

    public function __construct(MeasurementRepository $measurementRepository)
    {
        $this->measurementRepository = $measurementRepository;
    }

    public function execute(Sensor $sensor, MeasurementPayload $measurementPayload ): Sensor
    {
        $measurement = new Measurement(
            $sensor,
            $measurementPayload->c02Level(),
            $measurementPayload->registeredAt()
        );
        $sensor->addMeasurement($measurement);

        ControlC02AfterMeasurementRegistration::dispatch($sensor);

        return $sensor;
    }
}
