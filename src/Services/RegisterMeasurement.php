<?php

namespace Co2Control\Services;

use App\Jobs\ControlCo2IfRequireStartAlertListener;
use Co2Control\Constraints\ControlValues;
use Co2Control\Events\SensorHasReceivedMeasurement;
use Co2Control\Payloads\MeasurementPayload;
use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Repositories\MeasurementRepository;
use Co2Control\Repositories\PersistRepository;
use Illuminate\Contracts\Events\Dispatcher;

class RegisterMeasurement
{
    private MeasurementRepository $measurementRepository;
    private PersistRepository $persistRepository;
    private Dispatcher $dispatcher;

    public function __construct(MeasurementRepository $measurementRepository, PersistRepository $persistRepository, Dispatcher $dispatcher)
    {
        $this->measurementRepository = $measurementRepository;
        $this->persistRepository = $persistRepository;
        $this->dispatcher = $dispatcher;
    }

    public function execute(Sensor $sensor, MeasurementPayload $measurementPayload): Measurement
    {
        return $this->persistRepository->transactional(function ($em) use ($sensor, $measurementPayload) {
            $measurement = new Measurement(
                $sensor,
                $measurementPayload->c02Level(),
                $measurementPayload->registeredAt()
            );
            $sensor->addMeasurement($measurement);

            if ($measurement->getCo2Level() >= ControlValues::MIN_CO2_THRESHOLD) {
                $sensor->putInWarningStatus();
            }

            $this->persistRepository->persist($measurement);
            $this->persistRepository->persist($sensor);
            $this->persistRepository->flush();

            $this->dispatcher->dispatch(new SensorHasReceivedMeasurement($sensor, $measurement));

            return $measurement;
        });

    }
}
