<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom* : ',
                'required' => 'true'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom* : ',
                'required' => 'true'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email* : ',
                'required' => 'true'
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message* : ',
                'required' => 'true'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
