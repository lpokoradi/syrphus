<?php

namespace TestForge\Syrphus\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TestForge\Syrphus\SecurityBundle\Entity\Role;
use TestForge\Syrphus\SecurityBundle\Entity\User;
use TestForge\Syrphus\SecurityBundle\Repository\RoleRepository;

class UserController extends Controller
{
    public function listAction()
    {
        $adminRole = $this->getDoctrine()->getRepository('TestForgeSyrphusSecurityBundle:Role')->getRoleByName(RoleRepository::ROLE_DEFAULT_NAME)->getRole();
        $this->denyAccessUnlessGranted($adminRole, null, 'Unable to access this page!');

        $userRepository = $this->getDoctrine()->getRepository('TestForgeSyrphusSecurityBundle:User');

        $users = $userRepository->findAll();

        return $this->render(
            '@TestForgeSyrphusSecurity/User/list.html.twig',
            array('users' => $users)
        );
    }

    public function viewAction($id)
    {
        $defaultRole = $this->getDoctrine()->getRepository('TestForgeSyrphusSecurityBundle:Role')->getDefaultRole()->getRole();
        $this->denyAccessUnlessGranted($defaultRole, null, 'Unable to access this page!');

        $userRepository = $this->getDoctrine()->getRepository('TestForgeSyrphusSecurityBundle:User');

        $user = $userRepository->find($id);

        return $this->render(
            '@TestForgeSyrphusSecurity/User/view.html.twig',
            array('user' => $user)
        );
    }

    public function editAction($id)
    {
        $defaultRole = $this->getDoctrine()->getRepository('TestForgeSyrphusSecurityBundle:Role')->getDefaultRole()->getRole();
        $this->denyAccessUnlessGranted($defaultRole, null, 'Unable to access this page!');

        $userRepository = $this->getDoctrine()->getRepository('TestForgeSyrphusSecurityBundle:User');
        $user = $userRepository->find($id);

        $formBuilder = $this->createFormBuilder($user);

        $form = $this->get('customfieldprovider')->createForm($formBuilder, User::class, $id)->getForm();

        return $this->render(
            '@TestForgeSyrphusSecurity/User/edit.html.twig',
            array('form' => $form)
        );
    }
}
