<?php

namespace App\Jobs;

use Co2Control\Events\SensorHasReceivedMeasurement;
use Co2Control\Repositories\SensorRepository;
use Co2Control\Services\ControlMeasurementInOrderToStopAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ControlCo2IfRequireStopAlertListener implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private ControlMeasurementInOrderToStopAlert $controlInOrderToStopAlert;
    private SensorRepository $sensorRepository;

    public function __construct(
        ControlMeasurementInOrderToStopAlert $controlInOrderToStopAlert,
        SensorRepository $sensorRepository
    ) {
        $this->controlInOrderToStopAlert = $controlInOrderToStopAlert;
        $this->sensorRepository = $sensorRepository;
    }

    public function handle(SensorHasReceivedMeasurement $event)
    {
        if ($sensor = $this->sensorRepository->find($event->getSensorId())) {
            $this->controlInOrderToStopAlert->execute($sensor);
        }
    }
}
