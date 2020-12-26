```php


<?php

namespace App\Infrastructure\Doctrine\Mappings;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;
use src\Entities\Dummy;

class AmendmentMapping extends EntityMapping
{
    public function mapFor()
    {
        return Dummy::class;
    }

    public function map(Fluent $builder)
    {
        // Blameable
        $builder->timestamps('createdAt', 'updatedAt', 'chronosDateTime');
        $builder->manyToOne(TenantUser::class, 'createdBy')->nullable()->blameable()->onCreate();
        $builder->manyToOne(TenantUser::class, 'updatedBy')->nullable()->blameable()->onUpdate();
    }
}
