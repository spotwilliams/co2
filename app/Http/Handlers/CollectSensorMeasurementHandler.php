<?php

namespace App\Http\Handlers;

use App\Http\Requests\RegisterMeasurementRequest;
use Co2Control\Entities\Sensor;
use Co2Control\Repositories\SensorRepository;
use Co2Control\Services\RegisterMeasurementService;
use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Validation\ValidationException;

class CollectSensorMeasurementHandler extends Handler
{
    const ROUTE = '/v1/sensors/{uuid}/mesurements';
    const NAME = 'collect.sensor.measurements';

    public function __invoke(string $uuid, RegisterMeasurementRequest $request, RegisterMeasurementService $service, SensorRepository $sensorRepository)
    {
        try {
            $request->validate();
            /** @var Sensor $sensor */
            $sensor = $sensorRepository->get($uuid);

            $service->execute($sensor, $request);

            return response()->json(['message' => 'ok'], 201);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->first();

            return response()->json(['message' => $errors], 400);
        } catch (EntityNotFoundException $e) {
            return response()->json(['message' => 'Sensor not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
