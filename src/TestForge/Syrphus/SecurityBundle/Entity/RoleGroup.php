<?php

namespace TestForge\Syrphus\SecurityBundle\Entity;

use Doctrine\Common\Cache\SQLite3Cache;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * RoleGroup
 *
 * @ORM\Table(name="role_groups")
 * @ORM\Table(name="role_groups", uniqueConstraints={@ORM\UniqueConstraint(name="unique_parentrolegroups_name", columns={"parent_role_group_id", "name"})})
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\SecurityBundle\Repository\RoleGroupRepository")
 */
class RoleGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var RoleGroup
     *
     * @ORM\ManyToOne(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\RoleGroup")
     * @ORM\JoinColumn(fieldName="parent_role_group_id", referencedColumnName="id", nullable=true)
     */
    private $parentRoleGroup;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\Role")
     * @ORM\JoinTable(name="role_group_roles",
     *      joinColumns={@ORM\JoinColumn(name="role_group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")})
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roleGroups")
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add user
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\User $user
     *
     * @return RoleGroup
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     *
     * @return RoleGroup
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return RoleGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return RoleGroup
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set parentRoleGroup
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\RoleGroup $parentRoleGroup
     *
     * @return RoleGroup
     */
    public function setParentRoleGroup(\TestForge\Syrphus\SecurityBundle\Entity\RoleGroup $parentRoleGroup = null)
    {
        $this->parentRoleGroup = $parentRoleGroup;

        return $this;
    }

    /**
     * Get parentRoleGroup
     *
     * @return \TestForge\Syrphus\SecurityBundle\Entity\RoleGroup
     */
    public function getParentRoleGroup()
    {
        return $this->parentRoleGroup;
    }

    /**
     * Add role
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\Role $role
     *
     * @return RoleGroup
     */
    public function addRole(\TestForge\Syrphus\SecurityBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\Role $role
     *
     * @return RoleGroup
     */
    public function removeRole(Role $role)
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * Get roles
     *
     * @return Role[]
     */
    public function getRoles()
    {
        $sqlite3 = new SQLite3(__DIR__ . '/../../../../../var/cache/data.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $doctrineCache = new SQLite3Cache($sqlite3, 'cache');
        $id = 'role_group_' . $this->getName() . '_roles';

        if (!$doctrineCache->contains($id)) {
            $doctrineCache->save($id, array_unique($this->collectRoles()), 1800);
        }

        $roles = $doctrineCache->fetch($id);
        $sqlite3->close();
        return $roles;
    }

    protected function collectRoles()
    {
        $rolesArray = array();

        if (null != $this->parentRoleGroup) {
            $rolesArray = array_merge($rolesArray, $this->parentRoleGroup->getRoles());
        }

        foreach ($this->roles as $role) {
            $rolesArray[] = $role;
        }

        return $rolesArray;
    }
}
