<?php

namespace App\Infrastructure\Doctrine\Mappings;

use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Co2Control\Enumerables\SensorStatus;
use LaravelDoctrine\Fluent\Fluent;
use LaravelDoctrine\Fluent\EntityMapping;

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
    }
}
