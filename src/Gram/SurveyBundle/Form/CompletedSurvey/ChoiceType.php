<?php

namespace Gram\SurveyBundle\Form\CompletedSurvey;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ChoiceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class, [
                'required' => false
            ])
            ->add('name', TextType::class, [
                'required' => false
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $id = $form->get('id')->getData();
                $name = $form->get('name')->getData();
                if (!($id || $name)) {
                    $form->addError(new FormError('id or name is required'));
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'validation_groups' => ['Default'],
            'csrf_protection' => false
        ));
    }

}