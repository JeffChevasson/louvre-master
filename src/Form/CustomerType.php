<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom : ',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom : ',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email : ',
                'required' => true
            ])
            ->add('address', TextType::class, [
                'label' => ' Adresse : ',
                'required' => false
            ])
            ->add('postcode', TextType::class, [
                'label' => 'Code Postal : ',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville : ',
                'required' => false
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays : ',
                'preferred_choices' => [
                    'FR'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
