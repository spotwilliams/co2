<?php

namespace App\Jobs;

use Co2Control\Events\SensorHasReceivedMeasurement;
use Co2Control\Repositories\MeasurementRepository;
use Co2Control\Repositories\SensorRepository;
use Co2Control\Services\ControlMeasurementInOrderToStartAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ControlCo2IfRequireStartAlertListener implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private ControlMeasurementInOrderToStartAlert $controlInOrderToStartAlert;
    private SensorRepository $sensorRepository;
    private MeasurementRepository $measurementRepository;

    public function __construct(
        ControlMeasurementInOrderToStartAlert $controlInOrderToStartAlert,
        SensorRepository $sensorRepository,
        MeasurementRepository $measurementRepository
    ) {
        $this->controlInOrderToStartAlert = $controlInOrderToStartAlert;
        $this->sensorRepository = $sensorRepository;
        $this->measurementRepository = $measurementRepository;
    }

    public function handle(SensorHasReceivedMeasurement $event)
    {
        if ($sensor = $this->sensorRepository->find($event->getSensorId())) {
            $measurement = $this->measurementRepository->find($event->getMeasurementId());
            $this->controlInOrderToStartAlert->execute($sensor, $measurement);
        }
    }
}
