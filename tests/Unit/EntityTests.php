<?php

namespace Tests\Unit;

use Cake\Chronos\Chronos;
use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Enumerables\SensorStatus;
use Tests\TestCase;

class EntityTests extends TestCase
{
    public function testASensorCanReceiveOneMeasurement()
    {
        $sensor = new Sensor();
        $sensor->addMeasurement(new Measurement($co2Level = 2000, $co2MeasurementTime = Chronos::createFromFormat(Chronos::ATOM, '2019-02-01T18:55:47+00:00')));

        $measurements = $sensor->getMeasurements();
        /** @var Measurement $theRegisteredMeasurement */
        $theRegisteredMeasurement = current($measurements);

        $this->assertEquals(1, count($measurements));
        $this->assertEquals($co2Level, $theRegisteredMeasurement->getCo2Level());
        $this->assertEquals($co2MeasurementTime->toAtomString(), $theRegisteredMeasurement->getRegisteredAt()->toAtomString());
    }

    public function testASensorIsCreatedWithOkStatus()
    {
        $sensor = new Sensor();

        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());
    }
}
