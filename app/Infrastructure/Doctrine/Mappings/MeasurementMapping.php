<?php

namespace App\Infrastructure\Doctrine\Mappings;

use Co2Control\Entities\Measurement;
use Co2Control\Entities\Sensor;
use Digbang\DoctrineExtensions\Types\ChronosDateTimeType;
use LaravelDoctrine\Fluent\Fluent;

class MeasurementMapping extends EntityMapping
{
    public function mapFor()
    {
        return Measurement::class;
    }

    public function map(Fluent $builder)
    {
        $builder->integer('co2Level');
        $builder->field(ChronosDateTimeType::CHRONOS_DATETIME, 'registeredAt')->index();
        $builder->manyToOne(Sensor::class, 'sensor')->inversedBy('measurements');
    }
}
