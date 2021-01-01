<?php

namespace Co2Control\Services;

use Co2Control\Constraints\ControlValues;
use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Repositories\MeasurementRepository;
use Co2Control\Repositories\PersistRepository;

class ControlMeasurementInOrderToStopAlert
{
    private MeasurementRepository $measurementRepository;
    private PersistRepository $persistRepository;

    public function __construct(MeasurementRepository $measurementRepository, PersistRepository $persistRepository)
    {
        $this->measurementRepository = $measurementRepository;
        $this->persistRepository = $persistRepository;
    }

    public function execute(Sensor $sensor): Sensor
    {
        if (!$alert = $sensor->getActiveAlert()) {
            return $sensor;
        }

        /** @var Measurement[] $measurements */
        $measurements = $this->measurementRepository->getLastMeasurements($sensor, ControlValues::QUANTITY_OF_MEASUREMENTS_TO_OK);
        $measurementsLowerThanMinimal = $this->countOfMeasurementsLowerThanMinimal($measurements);

        if ($measurementsLowerThanMinimal === ControlValues::QUANTITY_OF_MEASUREMENTS_TO_OK) {
            $sensor->stopAlert();

            $this->persistRepository->transactional(function ($em) use ($sensor) {
                $this->persistRepository->persist($sensor);

                $this->persistRepository->flush();
            });

        }

        return $sensor;
    }

    private function countOfMeasurementsLowerThanMinimal(array $measurements): int
    {
        return array_reduce(
            $measurements,
            function ($carry, Measurement $measurement) {
                if ($measurement->getCo2Level() < ControlValues::MIN_CO2_THRESHOLD) {
                    $carry++;
                }
                return $carry;
            },
            0);
    }
}
