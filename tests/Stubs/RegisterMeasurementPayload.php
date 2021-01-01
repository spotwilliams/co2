<?php

namespace Tests\Stubs;

use Cake\Chronos\Chronos;
use Co2Control\Payloads\MeasurementPayload;

class RegisterMeasurementPayload implements MeasurementPayload
{
    private $co2Level;
    private $registeredAt;

    public function __construct(int $co2Level, string $registeredAt)
    {
        $this->co2Level = $co2Level;
        $this->registeredAt = $registeredAt;
    }

    public function c02Level(): int
    {
        return $this->co2Level;
    }

    public function registeredAt(): Chronos
    {
        return Chronos::createFromFormat(Chronos::ATOM, $this->registeredAt);
    }
}
