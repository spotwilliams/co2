<?php

namespace Co2Control\Repositories;

interface PersistRepository
{
    public function save($entity);

    public function remove($entity);

    public function persist($entity);

    public function flush($entity = null);

    public function clear($entity = null);

    public function transactional(callable $function);
}
