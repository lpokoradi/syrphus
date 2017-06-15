<?php
/**
 * Created by PhpStorm.
 * User: laszl
 * Date: 6/7/2017
 * Time: 8:29 PM
 */

namespace TestForge\Syrphus\SecurityBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

class RoleHierarchy implements RoleHierarchyInterface
{
    private $em;
    private $map;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->map = $this->buildRolesTree();
    }

    /**
     * Returns an array of all reachable roles by the given ones.
     *
     * Reachable roles are the roles directly assigned but also all roles that
     * are transitively reachable from them in the role hierarchy.
     *
     * @param RoleInterface[] $roles An array of directly assigned roles
     *
     * @return RoleInterface[] An array of all reachable roles
     */
    public function getReachableRoles(array $roles)
    {
        $reachableRoles = $roles;
        foreach ($roles as $role) {
            if (!isset($this->map[$role->getRole()])) {
                continue;
            }

            foreach ($this->map[$role->getRole()] as $r) {
                $reachableRoles[] = new Role($r);
            }
        }

        return $reachableRoles;
    }

    protected function buildRolesTree()
    {
        $hierarchy = array();

        $roles = $this->em->createQueryBuilder('r')
            ->from('TestForgeSyrphusSecurityBundle:Role')
            ->getQuery()
            ->getArrayResult();

        foreach ($roles as $role)
        {
            /* @var $role Role */
            if (count($role->getParentRoles()) > 0)
            {
                $roleChildren = array();

                foreach ($role->getParentRoles() as $parent)
                {
                    /* @var $child Role */
                    $roleParents[] = $parent->getRole();
                }

                $hierarchy[$role->getRole()] = $roleChildren;
            }
        }

        return $hierarchy;
    }
}