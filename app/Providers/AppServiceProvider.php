<?php

namespace App\Providers;

use App\Infrastructure\Doctrine\Repositories as Doctrine;
use Co2Control\Repositories;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private $classBindings = [
        //Generic Repositories
        Repositories\PersistRepository::class => Doctrine\DoctrinePersistRepository::class,
        Repositories\MeasurementRepository::class => Doctrine\DoctrineMeasurementRepository::class,
        Repositories\SensorRepository::class => Doctrine\DoctrineSensorRepository::class,
    ];

    public function register()
    {
        foreach ($this->classBindings as $abstract => $concrete) {
            if (is_array($concrete)) {
                $concrete = $concrete[$this->app->environment()] ?? $concrete['default'];
            }

            $this->app->bind($abstract, $concrete);
        }

        if (config('app.debug')) {
            // Register libraries that are only for debug purposes
        }
    }

    public function boot()
    {
    }
}
