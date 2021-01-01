<?php

namespace Co2Control\Services;

use Co2Control\Constraints\ControlValues;
use Co2Control\Entities\Alert;
use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Repositories\MeasurementRepository;
use Co2Control\Repositories\PersistRepository;

class ControlMeasurementInOrderToStartAlert
{
    private MeasurementRepository $measurementRepository;
    private PersistRepository $persistRepository;

    public function __construct(MeasurementRepository $measurementRepository, PersistRepository $persistRepository)
    {
        $this->measurementRepository = $measurementRepository;
        $this->persistRepository = $persistRepository;
    }

    public function execute(Sensor $sensor, Measurement $lastMeasurement): Sensor
    {
        if ($alert = $sensor->getActiveAlert()) {
            $this->verifyLastMeasurement($alert, $lastMeasurement);
        } else {
            $this->verifyLastBatchOfMeasurements($sensor);
        }
        return $sensor;
    }

    private function verifyLastMeasurement(Alert $alert, Measurement $lastMeasurement): void
    {
        if ($lastMeasurement->getCo2Level() >= ControlValues::MIN_CO2_THRESHOLD) {
            $this->persistRepository->transactional(function ($em) use ($alert, $lastMeasurement) {
                $alert->addMeasurement($lastMeasurement);

                $this->persistRepository->persist($alert);
                $this->persistRepository->flush();
            });

        }

    }

    private function verifyLastBatchOfMeasurements(Sensor $sensor)
    {
        /** @var Measurement[] $measurements */
        $measurements = $this->measurementRepository->getLastMeasurements($sensor, ControlValues::QUANTITY_OF_MEASUREMENTS_TO_WARN);
        $measurementsHigherThanMinimal = $this->countOfMeasurementsHigherThanMinimal($measurements);

        if ($measurementsHigherThanMinimal === ControlValues::QUANTITY_OF_MEASUREMENTS_TO_WARN) {

            $this->persistRepository->transactional(function ($em) use ($sensor, $measurements) {

                $alert = $sensor->putInAlertStatus()->getActiveAlert();
                foreach ($measurements as $alertMeasurement) {
                    $alert->addMeasurement($alertMeasurement);
                }

                $this->persistRepository->persist($sensor);
                $this->persistRepository->persist($alert);
                $this->persistRepository->flush();
            });
        }

    }

    private function countOfMeasurementsHigherThanMinimal(array $measurements): int
    {
        return array_reduce(
            $measurements,
            function ($carry, Measurement $measurement) {
                if ($measurement->getCo2Level() >= ControlValues::MIN_CO2_THRESHOLD) {
                    $carry++;
                }
                return $carry;
            },
            0);
    }
}
