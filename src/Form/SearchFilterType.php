<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\SearchFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', null, array('label' => false))
            ->add('model', null, array('label' => false))
            ->add('seatNumber', null, array('label' => false))
            ->add('maxSpeed', null, array('label' => false))
            ->add('weight', null, array('label' => false))
            ->add('power', null, array('label' => false))
            ->add('autonomy', null, array('label' => false))
            ->add('dayCost', null, array('label' => false))
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'label' => false,
                'required' => false,
                'placeholder' => 'type'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchFilter::class,
            'method' => 'get'
        ]);
    }
}
