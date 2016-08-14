<?php

namespace TestForge\Syrphus\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLogin
 *
 * @ORM\Table(name="user_logins")
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\SecurityBundle\Repository\UserLoginRepository")
 */
class UserLogin
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
     * @var User
     *
     * @ORM\OneToOne(targetEntity="TestForge\Syrphus\SecurityBundle\Entity\User", inversedBy="login", cascade={"all"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime Date and time of the last successful login
     * @ORM\Column(name="last_login_at", type="datetimetz", nullable=true)
     */
    private $lastLoginAt;

    /**
     * @var integer Total number of successful logins
     * @ORM\Column(name="total_login_count", type="integer")
     */
    private $totalLoginCount;

    /**
     * @var \DateTime Date and time of the last failed login attemps
     * @ORM\Column(name="last_failed_login_at", type="datetimetz", nullable=true)
     */
    private $lastFailedLoginAt;

    /**
     * @var integer Number of failed login attempts since last login
     * @ORM\Column(name="failed_login_count", type="integer")
     */
    private $failedLoginCount;

    /**
     * @var integer Total number of failed login attempts
     * @ORM\Column(name="total_failed_login_count", type="integer")
     */
    private $totalFailedLoginCount;

    /**
     * @var \DateTime Date and time of the last logout
     * @ORM\Column(name="last_logout_at", type="datetimetz", nullable=true)
     */
    private $lastLogoutAt;


    /**
     * @var integer Total number of logout
     * @ORM\Column(name="total_logout_count", type="integer")
     */
    private $totalLogoutCount;

    public function __construct()
    {
        $this->failedLoginCount = 0;
        $this->totalFailedLoginCount = 0;
        $this->totalLoginCount = 0;
        $this->totalLogoutCount = 0;
        $this->lastFailedLoginAt = null;
        $this->lastLoginAt = null;
        $this->lastLogoutAt = null;
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
     * Set lastLoginAt
     *
     * @param \DateTime $lastLoginAt
     *
     * @return UserLogin
     */
    public function setLastLoginAt($lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    /**
     * Get lastLoginAt
     *
     * @return \DateTime
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * Set totalLoginCount
     *
     * @param integer $totalLoginCount
     *
     * @return UserLogin
     */
    public function setTotalLoginCount($totalLoginCount)
    {
        $this->totalLoginCount = $totalLoginCount;

        return $this;
    }

    /**
     * Get totalLoginCount
     *
     * @return integer
     */
    public function getTotalLoginCount()
    {
        return $this->totalLoginCount;
    }

    /**
     * Set lastFailedLoginAt
     *
     * @param \DateTime $lastFailedLoginAt
     *
     * @return UserLogin
     */
    public function setLastFailedLoginAt($lastFailedLoginAt)
    {
        $this->lastFailedLoginAt = $lastFailedLoginAt;

        return $this;
    }

    /**
     * Get lastFailedLoginAt
     *
     * @return \DateTime
     */
    public function getLastFailedLoginAt()
    {
        return $this->lastFailedLoginAt;
    }

    /**
     * Set failedLoginCount
     *
     * @param integer $failedLoginCount
     *
     * @return UserLogin
     */
    public function setFailedLoginCount($failedLoginCount)
    {
        $this->failedLoginCount = $failedLoginCount;

        return $this;
    }

    /**
     * Get failedLoginCount
     *
     * @return integer
     */
    public function getFailedLoginCount()
    {
        return $this->failedLoginCount;
    }

    /**
     * Set totalFailedLoginCount
     *
     * @param integer $totalFailedLoginCount
     *
     * @return UserLogin
     */
    public function setTotalFailedLoginCount($totalFailedLoginCount)
    {
        $this->totalFailedLoginCount = $totalFailedLoginCount;

        return $this;
    }

    /**
     * Get totalFailedLoginCount
     *
     * @return integer
     */
    public function getTotalFailedLoginCount()
    {
        return $this->totalFailedLoginCount;
    }

    /**
     * Set user
     *
     * @param \TestForge\Syrphus\SecurityBundle\Entity\User $user
     *
     * @return UserLogin
     */
    public function setUser(\TestForge\Syrphus\SecurityBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TestForge\Syrphus\SecurityBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogoutAt()
    {
        return $this->lastLogoutAt;
    }

    /**
     * @param \DateTime $lastLogoutAt
     * @return UserLogin
     */
    public function setLastLogoutAt($lastLogoutAt)
    {
        $this->lastLogoutAt = $lastLogoutAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalLogoutCount()
    {
        return $this->totalLogoutCount;
    }

    /**
     * @param int $totalLogoutCount
     * @return UserLogin
     */
    public function setTotalLogoutCount($totalLogoutCount)
    {
        $this->totalLogoutCount = $totalLogoutCount;
        return $this;
    }
}
