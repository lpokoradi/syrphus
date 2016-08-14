<?php

namespace TestForge\Syrphus\CustomFieldBundle\Repository;

/**
 * EntityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    public function resolve($class)
    {
        return $this->createQueryBuilder()->select('e')->where('class = :class')->setParameter('class', $class)->getQuery()->getOneOrNullResult();
    }
}
