<?php

namespace Co2Control\Capabilities;

use Cake\Chronos\Chronos;

trait RegisterTimestamp
{
    /** @var Chronos */
    protected $createdAt;
    /** @var Chronos */
    protected $updatedAt;

    public function getCreatedAt(): Chronos
    {
        return Chronos::instance($this->createdAt);
    }

    public function getUpdatedAt(): Chronos
    {
        return Chronos::instance($this->updatedAt);
    }
}
