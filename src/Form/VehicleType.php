<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand',  null, array('label' => false))
            ->add('model',  null, array('label' => false))
            ->add('seatNumber',  null, array('label' => false))
            ->add('maxSpeed',  null, array('label' => false))
            ->add('weight',  null, array('label' => false))
            ->add('power',  null, array('label' => false))
            ->add('autonomy',  null, array('label' => false))
            ->add('dayCost',  null, array('label' => false))         
            ->add('description', TextareaType::class , array('label' => false))
            ->add('adress',  null, array('label' => false))
            ->add('type', EntityType::class, array(
                'class' => Type::class, // mapped => false 
                'label' => false)
            )
            ->add('picture' ,  FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false
            ]);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
