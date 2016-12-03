<?php

namespace Gram\SurveyBundle\Form\CompletedSurvey;

use Gram\SurveyBundle\Form\ObjectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CompletedSurveyRESTType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', UserRESTType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('survey', ObjectType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('answers', CollectionType::class, [
                'entry_type' => AnswerRESTType::class,
                'required' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ]);
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