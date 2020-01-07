<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('lastname', TextType::class , ['label' => false ] )
            ->add('firstname', TextType::class , ['label' => false ] )
            ->add('email',  EmailType::class , ['label' => false ] )         
            ->add('phoneNumber', TelType::class , ['label' => false ] )
            ->add('avatar',FileType::class,[
                'label' => false,
                'data_class' => null,
                'required' => false,
                'mapped' => false,
                
                     
            ]);   
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}