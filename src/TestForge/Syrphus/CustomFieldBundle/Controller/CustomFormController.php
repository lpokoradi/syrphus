<?php

namespace TestForge\Syrphus\CustomFieldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TestForge\Syrphus\CustomFieldBundle\Form\CustomFieldType;

class CustomFormController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/form", name="form_index", )
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(CustomFieldType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('TestForgeSyrphusCustomFieldBundle:CustomForm:index.html.twig',
            array(
                'form' => $form->createView(),
            ));
    }
}
