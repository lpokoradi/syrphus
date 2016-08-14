<?php

namespace TestForge\Syrphus\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TestForge\Syrphus\SecurityBundle\Entity\User;
use TestForge\Syrphus\SecurityBundle\Entity\UserLogin;
use TestForge\Syrphus\SecurityBundle\Form\RegisterFormType;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('TestForgeSyrphusSecurityBundle:Security:login.html.twig', [
            // last username entered by the user (if any)
            'last_username' => $helper->getLastUsername(),
            // last authentication error (if any)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }

    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(RegisterFormType::class);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')->encodePassword($user, $data["plainPassword"]);
            $user->setPassword($password);
            $user->setUsername($data["username"]);

            $userLogin = new UserLogin();
            $userLogin->setUser($user);

            $roleRepository = $this->getDoctrine()->getRepository('TestForgeSyrphusSecurityBundle:Role');
            $roleUser = $roleRepository->getDefaultRole();
            $user->addRole($roleUser);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->persist($userLogin);

            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('login');
        }

        return $this->render(
            '@TestForgeSyrphusSecurity/Security/register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function changePasswordAction()
    {

    }
}
