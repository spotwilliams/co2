<?php

namespace App\Infrastructure\Doctrine\Repositories;

use Digbang\Utils\Doctrine\QueryBuilderDecorator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Co2Control\Repositories\ReadRepository;

abstract class DoctrineReadRepository extends EntityRepository implements ReadRepository
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata($this->getEntity()));
    }

    /**
     * @param string $alias
     * @param null $indexBy
     *
     * @return QueryBuilderDecorator
     */
    public function createQueryBuilder($alias, $indexBy = null)
    {
        return (new QueryBuilderDecorator(
                $this->_em->createQueryBuilder()
            ))
            ->select($alias)
            ->from($this->_entityName, $alias, $indexBy);
    }

    abstract public function getEntity();

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $entity = $this->findOne($id);

        if (! $entity) {
            throw new EntityNotFoundException($this->getEntity());
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function findOne($id)
    {
        return $this->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->findAll();
    }

    public function refresh($entity)
    {
        $this->_em->refresh($entity);
    }
}
