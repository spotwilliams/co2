<?php

use App\Infrastructure\Doctrine\Embeddables;
use App\Infrastructure\Doctrine\Mappings;

return [
    // Embeddables
    Embeddables\SensorStatusMapping::class,

    // Entities
    Mappings\EntityMapping::class,
    Mappings\MeasurementMapping::class,
    Mappings\SensorMapping::class,
];
