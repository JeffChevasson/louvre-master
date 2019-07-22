<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('lastname', TextType::class, [
                'label' => 'Nom* : ',
                'required' => true
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom* : ',
                'required' => true
            ])

            ->add('country', CountryType::class, [
                'label' => 'Pays* : ',
                'required' => true
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance* : ',
                'required' => true
            ])
            ->add('discount', CheckboxType::class, [
                'label' => 'Tarif réduit de 10 euros
                - Tarif accordé sous certaines conditions (étudiant, employé du musée, d’un service du Ministère de la Culture, militaire…)',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }

}
