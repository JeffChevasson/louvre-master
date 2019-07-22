<?php


namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Visit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;




class VisitCustomerType extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', CollectionType::class,[
                'entry_type' => CustomerType::class

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visit::class


        ]);
    }
}