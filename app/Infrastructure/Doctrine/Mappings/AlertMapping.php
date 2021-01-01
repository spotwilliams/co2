<?php

namespace App\Infrastructure\Doctrine\Mappings;

use Co2Control\Entities\Alert;
use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Digbang\DoctrineExtensions\Types\ChronosDateTimeType;
use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;

class AlertMapping extends EntityMapping
{
    public function mapFor()
    {
        return Alert::class;
    }

    public function map(Fluent $builder)
    {
        $builder->field(ChronosDateTimeType::CHRONOS_DATETIME, 'startTime');
        $builder->field(ChronosDateTimeType::CHRONOS_DATETIME, 'endTime')->nullable();
        $builder->oneToMany(Measurement::class, 'measurements')->mappedBy('alert');
        $builder->manyToOne(Sensor::class, 'sensor');
    }
}
