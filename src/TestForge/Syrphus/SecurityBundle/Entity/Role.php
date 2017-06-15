<?php

namespace TestForge\Syrphus\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role as BaseRole;

/**
 * Role
 *
 * @ORM\Table(name="roles", uniqueConstraints={@ORM\UniqueConstraint(name="unique_parentroles_name", columns={"parent_role_id", "name"})})
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\SecurityBundle\Repository\RoleRepository")
 */
class Role extends BaseRole
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
     * @var ArrayCollection parentRoles
     *
     * @ORM\ManyToMany(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\Role")
     * @ORM\JoinTable(name="role_parent_roles",
     *     joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")})
     */
    private $parentRoles;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     *
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parentRoles = new ArrayCollection();
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return "ROLE_" . mb_strtoupper($this->name);
    }

    /**
     * Add parent role
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\Role $role
     *
     * @return Role
     */
    public function addParentRole(Role $role) {
        $this->parentRoles[] = $role;

        return $this;
    }

    /**
     * Remove role from parent roles
     *
     * @param Role $role
     *
     * @return Role
     */
    public function removeParentRole(Role $role)
    {
        $this->users->removeElement($role);

        return $this;
    }

    /**
     * Get parent roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParentRoles()
    {
        return $this->parentRoles;
    }

    /**
     * Add user
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\User $user
     *
     * @return Role
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
     * @return Role
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
}
