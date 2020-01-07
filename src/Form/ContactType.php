<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fieldName', null, array('label' => false))
            ->add('email', null, array('label' => false))
            ->add('about', ChoiceType::class, [
                'choices' => [
                    'Signalement' => "Signalement",
                    'Question' => "Question",
                    'Autre' => "Autre",
                ],
                'label' => false
            ])
            ->add('content', TextareaType::class, array('label' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
