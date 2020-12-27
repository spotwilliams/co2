<?php

namespace Co2Control\Entities;

use Co2Control\Enumerables\SensorStatus;
use Doctrine\Common\Collections\ArrayCollection;

class Sensor extends Entity
{
    private ArrayCollection $measurements;
    private SensorStatus $status;

    public function __construct()
    {
        parent::__construct();
        $this->measurements = new ArrayCollection();
        $this->status = SensorStatus::ok();
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

    public function getStatus(): SensorStatus
    {
        return $this->status;
    }
}
