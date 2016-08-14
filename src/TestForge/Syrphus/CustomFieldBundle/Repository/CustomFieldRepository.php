<?php

namespace TestForge\Syrphus\CustomFieldBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CustomFieldRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CustomFieldRepository extends EntityRepository
{
    public function findAllEntity($entityName)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT cf FROM TestForgeSyrphusCustomFieldBundle:CustomField cf WHERE cf.entity LIKE :entityName')
            ->setParameter('entityName', $entityName);

        return $query->getResult();
    }
}