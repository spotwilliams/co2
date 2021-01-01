<?php

namespace Co2Control\Entities;

use Cake\Chronos\Chronos;

class Measurement extends Entity
{
    private int $co2Level;
    private Chronos $registeredAt;
    private Sensor $sensor;
    private ?Alert $alert = null;

    public function __construct(Sensor $sensor, int $co2Level, Chronos $measurementTime)
    {
        parent::__construct();
        $this->sensor = $sensor;
        $this->co2Level = $co2Level;
        $this->registeredAt = $measurementTime;
    }

    public function getCo2Level(): int
    {
        return $this->co2Level;
    }

    public function getRegisteredAt(): Chronos
    {
        return $this->registeredAt;
    }
}
