<?php

namespace Co2Control\Entities;

use Co2Control\Constraints\ControlValues;
use Co2Control\Enumerables\SensorStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Sensor extends Entity
{
    private Collection $measurements;
    private Collection $alerts;
    private SensorStatus $status;
    private ?Alert $activeAlert = null;

    public function __construct()
    {
        parent::__construct();
        $this->measurements = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->status = SensorStatus::ok();
    }

    public function addMeasurement(Measurement $measurement): self
    {
        $this->measurements->add($measurement);

        return $this;
    }

    public function putInWarningStatus(): self
    {
        if (!$this->status->isAlert()) {
            $this->status = SensorStatus::warning();
        }
        return $this;
    }

    public function putInAlertStatus(): self
    {
        $this->status = SensorStatus::alert();

        $this->activeAlert = new Alert($this);
        $this->alerts->add($this->activeAlert);

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

    public function getActiveAlert(): ?Alert
    {
        return $this->activeAlert;
    }

    public function getAlerts(): array
    {
        return $this->alerts->toArray();
    }

    public function stopAlert(): self
    {
        $this->activeAlert->stop();
        $this->activeAlert = null;

        $this->status = SensorStatus::ok();

        return $this;
    }
}
