<?php

namespace Co2Control\Payloads;

use Cake\Chronos\Chronos;

interface MeasurementPayload
{
    public function c02Level(): int;
    public function registeredAt(): Chronos;
}
