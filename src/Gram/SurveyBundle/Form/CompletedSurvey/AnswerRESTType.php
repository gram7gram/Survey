<?php

namespace Gram\SurveyBundle\Form\CompletedSurvey;

use Gram\SurveyBundle\Form\ObjectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AnswerRESTType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', ObjectType::class, [
                'required' => true
            ])
            ->add('choices', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'required' => true,
                'allow_add' => true,
                'allow_delete' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'csrf_protection' => false,
            'validation_groups' => ["Default"]
        ));
    }

}