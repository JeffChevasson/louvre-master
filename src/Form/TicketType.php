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
                'label' => 'Nom : '
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom : ' ])

            ->add('country', CountryType::class, [
                'label' => 'Pays : '
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance : '
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
