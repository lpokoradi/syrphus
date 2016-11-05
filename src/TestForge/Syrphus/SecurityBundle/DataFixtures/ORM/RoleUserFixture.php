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
        $roleDefault = new Role();
        $roleDefault->setName(RoleRepository::ROLE_DEFAULT_NAME);
        $roleDefault->setParentRole(null);
        $roleDefault->setDescription("Default role for each user");
        $manager->persist($roleDefault);

        $roleRoot = new Role();
        $roleRoot->setName(RoleRepository::ROLE_ROOT_NAME);
        $roleRoot->setParentRole($roleDefault);
        $roleRoot->setDescription("The super admin user to access everything.");
        $manager->persist($roleRoot);

        $manager->flush();
    }
}