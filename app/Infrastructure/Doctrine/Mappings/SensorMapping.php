<?php

namespace App\Infrastructure\Doctrine\Mappings;

use Co2Control\Entities\Alert;
use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Enumerables\SensorStatus;
use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;

class SensorMapping extends EntityMapping
{
    public function mapFor()
    {
        return Sensor::class;
    }

    public function map(Fluent $builder)
    {
        $builder->embed(SensorStatus::class, 'status');
        $builder->oneToMany(Measurement::class, 'measurements')->mappedBy('sensor');
        $builder->oneToMany(Alert::class, 'alerts')->mappedBy('sensor');
        $builder->oneToOne(Alert::class, 'activeAlert')->cascadePersist();
    }
}
