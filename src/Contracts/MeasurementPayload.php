<?php

namespace Co2Control\Contracts;

use Cake\Chronos\Chronos;

interface MeasurementPayload
{
    public function c02Level(): int;
    public function registeredAt(): Chronos;
}
