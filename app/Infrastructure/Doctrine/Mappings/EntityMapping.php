<?php

namespace App\Infrastructure\Doctrine\Mappings;

use Co2Control\Entities\Entity;
use Digbang\DoctrineExtensions\Types\UuidType;
use LaravelDoctrine\Fluent\Fluent;
use LaravelDoctrine\Fluent\MappedSuperClassMapping;

class EntityMapping extends MappedSuperClassMapping
{
    use CanBlameTrait;

    public function mapFor()
    {
        return Entity::class;
    }

    public function map(Fluent $builder)
    {
        $builder->field(UuidType::UUID, 'id')->primary();
        $this->timestamps($builder);
        $builder->softDelete('deletedAt', 'chronosDateTime');
    }
}
