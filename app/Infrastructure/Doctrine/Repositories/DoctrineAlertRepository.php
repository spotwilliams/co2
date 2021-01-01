<?php

namespace App\Infrastructure\Doctrine\Repositories;

use Co2Control\Entities\Alert;
use Co2Control\Repositories\AlertRepository;

class DoctrineAlertRepository extends DoctrineReadRepository implements AlertRepository
{
    public function getEntity()
    {
        return Alert::class;
    }
}
