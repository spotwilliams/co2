<?php

namespace Co2Control\Entities;

use Cake\Chronos\Chronos;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Alert extends Entity
{
    private Collection $measurements;
    private Chronos $startTime;
    private ?Chronos $endTime = null;
    private Sensor $sensor;

    public function __construct(Sensor $sensor)
    {
        parent::__construct();
        $this->sensor = $sensor;
        $this->measurements = new ArrayCollection();
        $this->startTime = Chronos::now();
    }

    public function addMeasurement(Measurement $measurement): self
    {
        $this->measurements->add($measurement);

        return $this;
    }

    public function getMeasurements(): array
    {
        return $this->measurements->toArray();
    }

    public function getStartTime(): Chronos
    {
        return $this->startTime;
    }

    public function getEndTime(): ?Chronos
    {
        return $this->endTime;
    }

    public function stop(): self
    {
        $this->endTime = Chronos::now();

        return $this;
    }
}
