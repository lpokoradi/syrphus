<?php

namespace TestForge\Syrphus\CustomFieldBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $a = array(
            'choices' => array(
                'Maybe' => null,
                'Yes' => true,
                'No' => false,
            ),
            'constraints' => array(
                new NotBlank(),
                new Length(array('min' => 3)),
            ),
        );

        $b = json_encode($a);
        //print_r($b);

        $formBuilder = $this->createFormBuilder();

        $cfb = $this->get('customFormBuilder');
        $cfb->setFormBuilder($formBuilder)->setEntity('TEST');

        $form = $cfb->createForm()->getForm();

        return $this->render('TestForgeSyrphusCustomFieldBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
