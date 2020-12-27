<?php

namespace App\Infrastructure\Doctrine\Embeddables;

use Co2Control\Enumerables\SensorStatus;
use Digbang\Utils\Doctrine\Mappings\Embeddables\EnumMapping;

class SensorStatusMapping extends EnumMapping
{
    public function mapFor(): string
    {
        return SensorStatus::class;
    }
}
