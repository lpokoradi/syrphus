<?php
/**
 * Created by PhpStorm.
 * User: laszl
 * Date: 7/31/2016
 * Time: 2:34 PM
 */

namespace TestForge\Syrphus\SecurityBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TestForge\Syrphus\SecurityBundle\Entity\Role;
use TestForge\Syrphus\SecurityBundle\Repository\RoleRepository;

class RoleUserFixture implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roleUser = new Role();
        $roleUser->setName(RoleRepository::ROLE_DEFAULT_NAME);
        $roleUser->setParentRole(null);
        $roleUser->setDescription("Default role for each user");

        $manager->persist($roleUser);
        $manager->flush();
    }
}