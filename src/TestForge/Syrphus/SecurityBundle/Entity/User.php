<?php

namespace TestForge\Syrphus\SecurityBundle\Entity;

use Doctrine\Common\Cache\SQLite3Cache;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SQLite3;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\SecurityBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    const MAX_LOGIN_ATTEMPTS = 5;
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
     * @ORM\Column(name="username", type="string", length=64, unique=true, nullable=false)
     */
    private $username;

    /**
     * @var Password
     *
     * @ORM\OneToOne(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\Password", cascade={"all"})
     * @ORM\JoinColumn(name="current_password_id", referencedColumnName="id")
     */
    private $currentPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=64, unique=true, nullable=false)
     */
    private $salt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetimetz", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetimetz", nullable=true)
     */
    private $deletedAt;

    /**
     * @var UserLogin
     *
     * @ORM\OneToOne(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\UserLogin", mappedBy="user", cascade={"all"})
     */
    private $login;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="user_roles",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")})
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\RoleGroup", inversedBy="users")
     * @ORM\JoinTable(name="user_rolegroups",
     *     joinColumns={@ORM\JoinColumn(name="rolegroup_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")})
     */
    private $roleGroups;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\UserGroup", mappedBy="users")
     */
    private $userGroups;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\Password", mappedBy="user", cascade={"all"})
     */
    private $passwords;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_enabled", type="boolean", nullable=false)
     */
    private $enabled;

    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

        $this->roles = new ArrayCollection();
        $this->roleGroups = new ArrayCollection();

        $this->createdAt = new \DateTime("now");
        $this->deletedAt = null;
        $this->enabled = true;
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
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param String $passwordHash
     * @return User
     */
    public function setPassword($passwordHash)
    {
        if ($this->currentPassword != null)
            $this->currentPassword->setValidThru(new \DateTime("now"));

        $newPassword = new Password();
        $newPassword->setPasswordHash($passwordHash);
        $newPassword->setUser($this);

        $this->currentPassword = $newPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Add role
     *
     * @param Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove user
     *
     * @param Role $role
     */
    public function removeRole(Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Get users
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $sqlite3 = new SQLite3(__DIR__ . '/../../../../../var/cache/data.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $doctrineCache = new SQLite3Cache($sqlite3, 'cache');
        $id = 'user_' . $this->getUsername() . '_roles';

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

        foreach ($this->userGroups as $userGroup) {
            $rolesArray = array_merge($rolesArray, $userGroup->getRoles());
        }

        foreach ($this->roleGroups as $roleGroup) {
            $rolesArray = array_merge($rolesArray, $roleGroup->getRoles());
        }

        foreach ($this->roles as $role) {
            $rolesArray[] = $role->getRole();
        }

        return $rolesArray;
    }


    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        if ($this->deletedAt != null && $this->deletedAt < new \DateTime("now"))
            return false;
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        if (null != $this->login && $this->login->getFailedLoginCount() >= User::MAX_LOGIN_ATTEMPTS)
            return false;
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        if ($this->currentPassword == null)
            return false;
        $passwordValidThru = $this->currentPassword->getValidThru();
        if ($passwordValidThru != null && $passwordValidThru < new \DateTime("now"))
            return false;
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->salt,
            $this->username,
            $this->currentPassword,
            $this->enabled,
        ]);
        // TODO: Implement serialize() method.
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->salt,
            $this->username,
            $this->currentPassword,
            $this->enabled,
            ) = unserialize($serialized);
        // TODO: Implement unserialize() method.
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->currentPassword->getPasswordHash();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Nothing to do so far, implement if sensitive info is stored with the user

        //$this->currentPassword->setValidThru(new \DateTime("now"));
        //$this->currentPassword = null;
    }

    /**
     * Add roleGroup
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\RoleGroup $roleGroup
     *
     * @return User
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
     * Add userGroup
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\UserGroup $userGroup
     *
     * @return User
     */
    public function addUserGroup(\TestForge\Syrphus\SecurityBundle\Entity\UserGroup $userGroup)
    {
        $this->userGroups[] = $userGroup;

        return $this;
    }

    /**
     * Remove userGroup
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\UserGroup $userGroup
     */
    public function removeUserGroup(\TestForge\Syrphus\SecurityBundle\Entity\UserGroup $userGroup)
    {
        $this->userGroups->removeElement($userGroup);
    }

    /**
     * Get userGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }

    /**
     * Get passwords
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPasswords()
    {
        return $this->passwords;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return UserLogin
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param UserLogin $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @param boolean $enabled
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }
}
