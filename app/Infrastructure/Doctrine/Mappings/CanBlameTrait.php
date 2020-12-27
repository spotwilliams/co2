<?php

namespace App\Infrastructure\Doctrine\Mappings;

use LaravelDoctrine\Fluent\Fluent;

trait CanBlameTrait
{
    public function timestamps(Fluent $builder): void
    {
        $builder->timestamps('createdAt', 'updatedAt', 'chronosDateTime');
    }
}
