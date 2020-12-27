<?php

namespace App\Jobs;

use Co2Control\Entities\Sensor;
use Co2Control\Repositories\SensorRepository;
use Co2Control\Services\ControlC02Level;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ControlC02AfterMeasurementRegistration implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private string $sensorId;

    public function __construct(Sensor $sensor)
    {
        $this->sensorId = $sensor->getId();
    }

    public function handle(ControlC02Level $controlC02Level, SensorRepository $sensorRepository)
    {
        if ($sensor = $sensorRepository->find($this->sensorId)) {
            $controlC02Level->execute($sensor);
        }
    }
}
