<?php

namespace TestForge\Syrphus\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserGroup
 *
 * @ORM\Table(name="user_groups", uniqueConstraints={@ORM\UniqueConstraint(name="unique_parentusergroups_name", columns={"parent_user_group_id", "name"})})
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\SecurityBundle\Repository\UserGroupRepository")
 */
class UserGroup
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
     * @var UserGroup
     *
     * @ORM\ManyToOne(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\UserGroup")
     * @ORM\JoinColumn(fieldName="parent_user_group_id", referencedColumnName="id", nullable=true)
     */
    private $parentUserGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=false)
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\Role")
     * @ORM\JoinTable(name="user_group_roles",
     *      joinColumns={@ORM\JoinColumn(name="user_group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")})
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\RoleGroup")
     * @ORM\JoinTable(name="user_group_role_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_group_id", referencedColumnName="id")})
     */
    private $roleGroups;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\User", inversedBy="userGroups")
     * @ORM\JoinTable(name="usergroup_users",
     *     joinColumns={@ORM\JoinColumn(name="usergroup_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")})
     */
    private $users;

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
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->roleGroups = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return UserGroup
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
     * @return UserGroup
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
     * Add role
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\Role $role
     *
     * @return UserGroup
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
     */
    public function removeRole(\TestForge\Syrphus\SecurityBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Add roleGroup
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\RoleGroup $roleGroup
     *
     * @return UserGroup
     */
    public function addRoleGroup(\TestForge\Syrphus\SecurityBundle\Entity\RoleGroup $roleGroup)
    {
        $this->roleGroups[] = $roleGroup;

        return $this;
    }

    /**
     * Remove roleGroup
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\RoleGroup $roleGroup
     */
    public function removeRoleGroup(\TestForge\Syrphus\SecurityBundle\Entity\RoleGroup $roleGroup)
    {
        $this->roleGroups->removeElement($roleGroup);
    }

    /**
     * Get roleGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoleGroups()
    {
        return $this->roleGroups;
    }

    /**
     * Add user
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\User $user
     *
     * @return UserGroup
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
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
     * @return UserGroup
     */
    public function getParentUserGroup()
    {
        return $this->parentUserGroup;
    }

    /**
     * @return UserGroup
     * @param UserGroup $parentUserGroup
     */
    public function setParentUserGroup($parentUserGroup)
    {
        $this->parentUserGroup = $parentUserGroup;
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
        $id = 'user_group_' . $this->getName() . '_roles';

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

        if (null != $this->parentUserGroup) {
            $rolesArray = array_merge($rolesArray, $this->parentUserGroup->getRoles());
        }

        foreach ($this->roleGroups as $roleGroup) {
            $rolesArray = array_merge($rolesArray, $roleGroup->getRoles());
        }

        foreach ($this->roles as $role) {
            $rolesArray[] = $role;
        }

        return $rolesArray;
    }
}
