<?php

namespace Co2Control\Capabilities;

use Co2Control\Entities\User;

trait CanBlame
{
    /** @var User */
    private $createdBy;
    /** @var User | null */
    private $updatedBy;

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }
}
