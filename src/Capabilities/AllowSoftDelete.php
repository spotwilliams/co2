<?php

namespace Co2Control\Capabilities;

use Cake\Chronos\Chronos;

trait AllowSoftDelete
{
    /** @var Chronos */
    protected $deletedAt;

    public function getDeletedAt(): Chronos
    {
        return Chronos::instance($this->deletedAt);
    }
}
