<?php

namespace Co2Control\Entities;

use Co2Control\Capabilities\AllowSoftDelete;
use Co2Control\Capabilities\RegisterTimestamp;
use Ramsey\Uuid\Uuid;

abstract class Entity
{
    use AllowSoftDelete;
    use RegisterTimestamp;

    /** @var Uuid */
    protected $id;

    protected function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }
}
