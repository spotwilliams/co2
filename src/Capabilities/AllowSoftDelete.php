<?php

namespace Co2Control\Capabilities;

use Cake\Chronos\Chronos;

trait AllowSoftDelete
{
    protected Chronos $deletedAt;

    public function getDeletedAt(): Chronos
    {
        return Chronos::instance($this->deletedAt);
    }
}
