<?php

use App\Http\Handlers\CollectSensorMeasurementHandler;
use Illuminate\Support\Facades\Route;

Route::post(
    CollectSensorMeasurementHandler::ROUTE,
    CollectSensorMeasurementHandler::class
)->name(CollectSensorMeasurementHandler::NAME);
