<?php
/**
 * Created by PhpStorm.
 * User: Laszlo
 * Date: 2016-06-12
 * Time: 12:34 PM
 */

namespace TestForge\Syrphus\CustomFieldBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TestForge\Syrphus\CustomFieldBundle\Entity\FieldType;
use TestForge\Syrphus\CustomFieldBundle\Entity\FieldTypeOption;

class CustomFieldType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Name: ',
        ])->add('type', EntityType::class, [
            'class' => 'TestForgeSyrphusCustomFieldBundle:FieldType',
            'choice_label' => 'name',
            'group_by' => 'typeClass',
            'label' => 'Type: ',
            'placeholder' => '',
        ]);

        $formModifier = function (FormInterface $form, FieldType $fieldType = null) {
            $fieldTypeOptions = null === $fieldType ? array() : $fieldType->getOptions();

            $form->add('position', EntityType::class, array(
                'class' => 'TestForgeSyrphusCustomFieldBundle:FieldTypeOption',
                'placeholder' => '',
                'choice_label' => 'name',
                'choices' => $fieldTypeOptions,
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                $formModifier($event->getForm(), null === $data ? null : $data->getType());
            }
        );

        $builder->get('type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $fieldType = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $fieldType);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver); // TODO: Change the autogenerated stub
    }
}