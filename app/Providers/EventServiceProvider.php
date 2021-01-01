<?php

namespace App\Providers;

use App\Jobs\ControlCo2IfRequireStartAlertListener;
use App\Jobs\ControlCo2IfRequireStopAlertListener;
use Co2Control\Events\SensorHasReceivedMeasurement;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SensorHasReceivedMeasurement::class => [
            ControlCo2IfRequireStartAlertListener::class,
            ControlCo2IfRequireStopAlertListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
