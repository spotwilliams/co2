<?php

namespace App\Infrastructure\Doctrine\Mappings;

use Co2Control\Entities\User;
use LaravelDoctrine\Fluent\Fluent;

trait CanBlameTrait
{
    public function blameCapabilities(Fluent $builder): void
    {
        $builder->timestamps('createdAt', 'updatedAt', 'chronosDateTime');
        $builder->manyToOne(User::class, 'createdBy')->nullable()->blameable()->onCreate();
        $builder->manyToOne(User::class, 'updatedBy')->nullable()->blameable()->onUpdate();
    }
}
