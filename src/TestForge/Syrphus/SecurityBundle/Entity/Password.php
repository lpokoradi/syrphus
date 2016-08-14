<?php

namespace TestForge\Syrphus\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TestForge\Syrphus\UtilBundle\Exception\InvalidOperationException;

/**
 * Password
 *
 * @ORM\Table(name="passwords")
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\SecurityBundle\Repository\PasswordRepository")
 */
class Password
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
     * @ORM\Column(name="password_hash", type="string", nullable=false)
     */
    private $passwordHash;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\User", inversedBy="passwords")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="valid_from", type="datetimetz", nullable=false)
     */
    private $validFrom;

    /**
     * @var \DateTime
     * @ORM\Column(name="valid_thru", type="datetimetz", nullable=true)
     */
    private $validThru;

    /**
     * Password constructor.
     */
    public function __construct()
    {
        $this->user = null;
        $this->validFrom = new \DateTime("now");
        $this->validThru = null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @param string $passwordHash
     * @return Password
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Password
     * @throws InvalidOperationException
     */
    public function setUser($user)
    {
        if ($this->user != null)
            throw new InvalidOperationException();
        $this->user = $user;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @return \DateTime
     */
    public function getValidThru()
    {
        return $this->validThru;
    }

    /**
     * @param \DateTime $validThru
     * @return Password
     */
    public function setValidThru($validThru)
    {
        $this->validThru = $validThru;
        return $this;
    }
}