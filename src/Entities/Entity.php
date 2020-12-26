<?php

namespace Co2Control\Entities;

use Co2Control\Capabilities\CanBlame;
use Co2Control\Capabilities\AllowSoftDelete;
use Co2Control\Capabilities\RegisterTimestamp;

abstract class Entity
{
    use AllowSoftDelete;
    use CanBlame;
    use RegisterTimestamp;

    /** @var int */
    protected $id;


    public function getId(): int
    {
        return $this->id;
    }
}
