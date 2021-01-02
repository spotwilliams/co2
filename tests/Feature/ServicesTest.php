<?php

namespace Tests\Feature;

use Co2Control\Entities\Alert;
use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Enumerables\SensorStatus;
use Co2Control\Repositories\AlertRepository;
use Co2Control\Repositories\SensorRepository;
use Co2Control\Services\RegisterMeasurementService;
use Tests\Stubs\RegisterMeasurementPayload;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    private SensorRepository $sensorRepository;
    private AlertRepository $alertRepository;
    private RegisterMeasurementService $registerMeasurement;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sensorRepository = $this->app->get(SensorRepository::class);
        $this->alertRepository = $this->app->get(AlertRepository::class);
        $this->registerMeasurement = $this->app->get(RegisterMeasurementService::class);
    }

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

        $sensor = new Sensor();

        $this->executeService($sensor, $measurements);

        $sensor = $this->sensorRepository->get($sensor->getId());
        $this->commonAssertions($measurements, $sensor);
    }

    public function testWhenAMeasurementsIsAbove2000SensorStatusChangesToWarn()
    {
        $measurements = [
            [190, '2019-02-01T18:55:47+00:00'],
            [290, '2019-02-01T18:55:48+00:00'],
            [950, '2019-02-01T18:55:49+00:00'],
            [50, '2019-02-01T18:55:50+00:00'],
            [2010, '2019-02-01T18:55:51+00:00'],
            [100, '2019-02-01T18:52:47+00:00'],
            [500, '2019-02-01T18:53:47+00:00'],
        ];
        $sensor = new Sensor();
        // Sensor Status at the beginning
        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());

        $this->executeService($sensor, $measurements);

        $sensor = $this->sensorRepository->get($sensor->getId());

        $this->commonAssertions($measurements, $sensor);
        $this->assertEquals(SensorStatus::WARN, $sensor->getStatus());
    }

    public function testWhenThreeConsecutiveMeasurementsAreAbove2000SensorStatusChangesToAlert()
    {
        $measurements = [
            [100, '2019-02-01T18:55:47+00:00'],
            [200, '2019-02-01T18:55:48+00:00'],
            [300, '2019-02-01T18:55:49+00:00'],
            [400, '2019-02-01T18:55:50+00:00'],
            [2010, '2019-02-01T18:55:51+00:00'],
            [2011, '2019-02-01T18:55:52+00:00'],
            [2012, '2019-02-01T18:55:53+00:00'],
        ];
        $sensor = new Sensor();

        // Sensor Status at the beginning
        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());

        $this->executeService($sensor, $measurements);

        $sensor = $this->sensorRepository->get($sensor->getId());

        $this->commonAssertions($measurements, $sensor);
        $this->assertEquals(SensorStatus::ALERT, $sensor->getStatus());
    }

    public function testWhenThreeNonConsecutiveMeasurementsAreAbove2000SensorStatusRemainsAsWarning()
    {
        $measurements = [
            [100, '2019-02-01T18:55:47+00:00'],
            [200, '2019-02-01T18:55:48+00:00'],
            [300, '2019-02-01T18:55:49+00:00'],
            [400, '2019-02-01T18:55:50+00:00'],
            [2010, '2019-02-01T18:55:51+00:00'],
            [2011, '2019-02-01T18:55:52+00:00'],
            [600, '2019-02-01T18:55:53+00:00'],
            [2012, '2019-02-01T18:55:54+00:00'],
        ];
        $sensor = new Sensor();
        // Sensor Status at the beginning
        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());

        $this->executeService($sensor, $measurements);

        $sensor = $this->sensorRepository->get($sensor->getId());

        $this->commonAssertions($measurements, $sensor);
        $this->assertEquals(SensorStatus::WARN, $sensor->getStatus());
    }

    public function testWhenThreeConsecutiveMeasurementsAreAbove2000SensorAnAlertIsStored()
    {
        $measurements = [
            [100, '2019-02-01T18:55:47+00:00'],
            [200, '2019-02-01T18:55:48+00:00'],
            [300, '2019-02-01T18:55:49+00:00'],
            [400, '2019-02-01T18:55:50+00:00'],
            [2010, '2019-02-01T18:55:51+00:00'],
            [2011, '2019-02-01T18:55:52+00:00'],
            [2012, '2019-02-01T18:55:54+00:00'],
        ];
        $sensor = new Sensor();
        // Sensor Status at the beginning
        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());

        $this->executeService($sensor, $measurements);

        $sensor = $this->sensorRepository->get($sensor->getId());

        $this->commonAssertions($measurements, $sensor);
        $this->assertEquals(SensorStatus::ALERT, $sensor->getStatus());

        /** @var Alert $alert */
        $alert = $this->alertRepository->find($sensor->getActiveAlert()->getId());
        $this->assertNotNull($alert);
        $this->assertNotNull($alert->getStartTime());
        $this->assertNull($alert->getEndTime());

        /** @var Measurement $alertMeasurement */
        foreach ($alert->getMeasurements() as $alertMeasurement) {
            $this->assertContains($alertMeasurement->getCo2Level(), [2010, 2011, 2012]);
        }
    }

    public function testWhenSensorHasAnAlertNewMeasurementHigherThanMinimumAreRegisteredToTheAlert()
    {
        $measurements = [
            [100, '2019-02-01T18:55:47+00:00'],
            [200, '2019-02-01T18:55:48+00:00'],
            [300, '2019-02-01T18:55:49+00:00'],
            [400, '2019-02-01T18:55:50+00:00'],
        ];

        $alertedMeasurements = [
            [2010, '2019-02-01T18:55:51+00:00'],
            [2011, '2019-02-01T18:55:52+00:00'],
            [2012, '2019-02-01T18:55:54+00:00'],
        ];

        $otherMeasurements = [
            [200, '2019-02-01T19:55:54+00:00'],
            [300, '2019-02-01T19:55:55+00:00'],
        ];

        $otherAlertedMeasurements = [
            [3010, '2019-02-01T20:55:51+00:00'],
            [3011, '2019-02-01T20:55:52+00:00'],
            [3012, '2019-02-01T20:55:53+00:00'],
        ];

        $measurementHasToBePartOfAlert = [];

        $sensor = new Sensor();
        // Sensor Status at the beginning
        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());

        $this->executeService($sensor, $measurements);
        $this->executeService($sensor, $alertedMeasurements, $measurementHasToBePartOfAlert);
        $this->executeService($sensor, $otherMeasurements);
        $this->executeService($sensor, $otherAlertedMeasurements, $measurementHasToBePartOfAlert);

        /** @var Alert $alert */
        $alert = $this->alertRepository->find($sensor->getActiveAlert()->getId());
        $measurementsOfTheAlert = $alert->getMeasurements();

        $this->commonAssertions(array_merge($measurements, $alertedMeasurements, $otherMeasurements, $otherAlertedMeasurements), $sensor);
        $this->assertEquals(SensorStatus::ALERT, $sensor->getStatus());
        $this->assertNotNull($alert);
        $this->assertNotNull($alert->getStartTime());
        $this->assertNull($alert->getEndTime());

        $this->assertEquals(count($measurementHasToBePartOfAlert), count($measurementsOfTheAlert));

        /** @var Measurement $mToSearch */
        foreach ($measurementHasToBePartOfAlert as $mToSearch) {
            $found = array_filter($measurementsOfTheAlert, fn (Measurement $mInAlert) => $mInAlert->getId() === $mToSearch->getId());
            $this->assertEquals(1, count($found));
        }
    }

    public function testWhenAlertIsActiveAndHaveThreeConsecutivesMeasurementsWithLowerThanMinimalAlertIsClosed()
    {
        $measurements = [
            [100, '2019-02-01T18:55:47+00:00'],
            [200, '2019-02-01T18:55:48+00:00'],
            [300, '2019-02-01T18:55:49+00:00'],
            [400, '2019-02-01T18:55:50+00:00'],
        ];

        $alertedMeasurements = [
            [2010, '2019-02-01T18:55:51+00:00'],
            [2011, '2019-02-01T18:55:52+00:00'],
            [2012, '2019-02-01T18:55:54+00:00'],
        ];

        $otherMeasurements = [
            [200, '2019-02-01T19:55:54+00:00'],
            [300, '2019-02-01T19:55:55+00:00'],
        ];

        $otherAlertedMeasurements = [
            [3010, '2019-02-01T20:55:51+00:00'],
            [3011, '2019-02-01T20:55:52+00:00'],
            [3012, '2019-02-01T20:55:53+00:00'],
        ];

        $measurementsWithLowerLeves = [
            [100, '2019-02-01T20:55:54+00:00'],
            [200, '2019-02-01T20:55:55+00:00'],
            [300, '2019-02-01T20:55:56+00:00'],
        ];

        $measurementHasToBePartOfAlert = [];

        $sensor = new Sensor();
        // Sensor Status at the beginning
        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());

        $this->executeService($sensor, $measurements);
        $this->executeService($sensor, $alertedMeasurements, $measurementHasToBePartOfAlert);
        $this->executeService($sensor, $otherMeasurements);
        $this->executeService($sensor, $otherAlertedMeasurements, $measurementHasToBePartOfAlert);
        $this->executeService($sensor, $measurementsWithLowerLeves);

        /** @var Alert $alert */
        $alert = current($sensor->getAlerts());
        $measurementsOfTheAlert = $alert->getMeasurements();

        $this->commonAssertions(array_merge($measurements, $alertedMeasurements, $otherMeasurements, $otherAlertedMeasurements, $measurementsWithLowerLeves), $sensor);
        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());
        $this->assertNull($sensor->getActiveAlert());
        $this->assertNotNull($alert->getStartTime());
        $this->assertNotNull($alert->getEndTime());

        $this->assertEquals(count($measurementHasToBePartOfAlert), count($measurementsOfTheAlert));

        /** @var Measurement $mToSearch */
        foreach ($measurementHasToBePartOfAlert as $mToSearch) {
            $found = array_filter($measurementsOfTheAlert, fn (Measurement $mInAlert) => $mInAlert->getId() === $mToSearch->getId());
            $this->assertEquals(1, count($found));
        }
    }

    public function testWhenAlertIsActiveAndHaveThreeNonConsecutivesMeasurementsWithLowerThanMinimalAlertRemainsOpen()
    {
        $measurements = [
            [100, '2019-02-01T18:55:47+00:00'],
            [200, '2019-02-01T18:55:48+00:00'],
            [300, '2019-02-01T18:55:49+00:00'],
            [400, '2019-02-01T18:55:50+00:00'],
        ];

        $alertedMeasurements = [
            [2010, '2019-02-01T18:55:51+00:00'],
            [2011, '2019-02-01T18:55:52+00:00'],
            [2012, '2019-02-01T18:55:54+00:00'],
        ];

        $otherMeasurements = [
            [200, '2019-02-01T19:55:54+00:00'],
            [300, '2019-02-01T19:55:55+00:00'],
        ];

        $otherAlertedMeasurements = [
            [3010, '2019-02-01T20:55:51+00:00'],
            [3011, '2019-02-01T20:55:52+00:00'],
            [3012, '2019-02-01T20:55:53+00:00'],
        ];

        $measurementsWithLowerLeves = [
            [100, '2019-02-01T20:55:54+00:00'],
            [2001, '2019-02-01T20:55:55+00:00'],
            [300, '2019-02-01T20:55:56+00:00'],
            [302, '2019-02-01T20:55:57+00:00'],
        ];

        $sensor = new Sensor();
        // Sensor Status at the beginning
        $this->assertEquals(SensorStatus::OK, $sensor->getStatus());

        $this->executeService($sensor, $measurements);
        $this->executeService($sensor, $alertedMeasurements);
        $this->executeService($sensor, $otherMeasurements);
        $this->executeService($sensor, $otherAlertedMeasurements);
        $this->executeService($sensor, $measurementsWithLowerLeves);

        /** @var Alert $alert */
        $alert = current($sensor->getAlerts());

        $this->commonAssertions(array_merge($measurements, $alertedMeasurements, $otherMeasurements, $otherAlertedMeasurements, $measurementsWithLowerLeves), $sensor);
        $this->assertEquals(SensorStatus::ALERT, $sensor->getStatus());
        $this->assertNotNull($sensor->getActiveAlert());
        $this->assertNotNull($alert->getStartTime());
        $this->assertNull($alert->getEndTime());
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

    /**
     * @param RegisterMeasurementService $registerMeasurement
     *
     * @return mixed
     */
    private function executeService(Sensor $sensor, array $measurements, array &$output = [])
    {
        foreach ($measurements as $m) {
            $output[] = $this->registerMeasurement->execute($sensor, new RegisterMeasurementPayload($m[0], $m[1]));
        }

        return $m;
    }
}
