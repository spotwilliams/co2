<?php

namespace App\Infrastructure\Doctrine\Traits;

use LaravelDoctrine\Fluent\Fluent;
use SmartTrust\Entities\Tenant;

trait ManyToOneTenant
{
    private function mapTenant(Fluent $builder)
    {
        $builder->manyToOne(Tenant::class, 'tenant');
    }
}
