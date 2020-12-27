<?php

namespace Tests\Feature;

use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Enumerables\SensorStatus;
use Co2Control\Services\RegisterMeasurement;
use Tests\Stubs\RegisterMeasurementPayload;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    public function testCanRegisterSeveralC02MeasurementsForSensor()
    {
        $measurements = [
           [1950, '2019-02-01T18:55:47+00:00'],
           [2950, '2019-02-01T18:55:48+00:00'],
           [950, '2019-02-01T18:55:49+00:00'],
           [50, '2019-02-01T18:55:50+00:00'],
           [19, '2019-02-01T18:55:51+00:00'],
           [1500, '2019-02-01T18:52:47+00:00'],
           [5000, '2019-02-01T18:53:47+00:00'],
       ];
        /** @var RegisterMeasurement $registerMeasurement */
        $registerMeasurement = $this->app->get(RegisterMeasurement::class);
        $sensor = new Sensor();

        foreach ($measurements as $m) {
            $registerMeasurement->execute($sensor, new RegisterMeasurementPayload($m[0], $m[1]));
        }

        $this->commonAssertions($measurements, $sensor);
    }

    public function testWhenMoreThanThreeMeasurementsAreAbove2000SensorStatusIsWarn()
    {
        $measurements = [
            [1950, '2019-02-01T18:55:47+00:00'],
            [2950, '2019-02-01T18:55:48+00:00'],
            [950, '2019-02-01T18:55:49+00:00'],
            [50, '2019-02-01T18:55:50+00:00'],
            [19, '2019-02-01T18:55:51+00:00'],
            [1500, '2019-02-01T18:52:47+00:00'],
            [5000, '2019-02-01T18:53:47+00:00'],
        ];
        /** @var RegisterMeasurement $registerMeasurement */
        $registerMeasurement = $this->app->get(RegisterMeasurement::class);
        $sensor = new Sensor();
        // Sensor Status at the beginning
        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());

        foreach ($measurements as $m) {
            $registerMeasurement->execute($sensor, new RegisterMeasurementPayload($m[0], $m[1]));
        }

        $this->commonAssertions($measurements, $sensor);
        $this->assertEquals(SensorStatus::WARN, $sensor->getStatus());
    }

    private function commonAssertions(array $measurements, Sensor $sensor): void
    {
        $this->assertEquals(count($measurements), count($sensor->getMeasurements()));

        /** @var Measurement[] $registeredMeasurements */
        $registeredMeasurements = $sensor->getMeasurements();
        foreach ($measurements as $key => $m) {
            $this->assertEquals($m[0], $registeredMeasurements[$key]->getCo2Level());
            $this->assertEquals($m[1], $registeredMeasurements[$key]->getRegisteredAt()->toAtomString());
        }
    }
}
