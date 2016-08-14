<?php

namespace TestForge\Syrphus\SecurityBundle\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Logout\SessionLogoutHandler;
use TestForge\Syrphus\SecurityBundle\Entity\User;
use TestForge\Syrphus\SecurityBundle\Entity\UserLogin;

class AuthenticationListener extends SessionLogoutHandler
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        try {
            $username = $event->getAuthenticationToken()->getUsername();

            $login = $this->getUserLogin($username);

            if (null != $login) {
                $login->setFailedLoginCount($login->getFailedLoginCount() + 1);
                $login->setLastFailedLoginAt(new \DateTime("now"));
                $login->setTotalFailedLoginCount($login->getTotalFailedLoginCount() + 1);
            }
            $this->em->flush();
        } catch (\Exception $e) {

        }

        // executes on failed login
    }

    public function onAuthenticationSuccess(InteractiveLoginEvent $event)
    {
        try {
            $username = $event->getAuthenticationToken()->getUsername();

            $login = $this->getUserLogin($username);

            if (null != $login) {
                $login->setFailedLoginCount(0);
                $login->setTotalLoginCount($login->getTotalLoginCount() + 1);
                $login->setLastLoginAt(new \DateTime("now"));
            }

            $this->em->flush();
        } catch (\Exception $e) {

        }
        // executes on successful login
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        try {
            $username = $token->getUsername();

            $login = $this->getUserLogin($username);

            if (null != $login) {
                $login->setTotalLogoutCount($login->getTotalLogoutCount() + 1);
                $login->setLastLogoutAt(new \DateTime("now"));
            }

            $this->em->flush();
        } catch (\Exception $e) {

        }

        parent::logout($request, $response, $token); // TODO: Change the autogenerated stub
    }

    /**
     * @param $username
     *
     * @return UserLogin|null Get the login statistic for the username
     */
    protected function getUserLogin($username)
    {
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->loadUserByUsername($username);

        if (null != $user && $user instanceof User)
            return $user->getLogin();
        return null;
    }
}