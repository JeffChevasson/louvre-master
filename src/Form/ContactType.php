<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class  ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, [
            'label' => 'label.lastname',
            'required' => true])
            ->add('firstname', TextType::class, [
                'label' => 'label.firstname',
                'required' => true])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'required' => true])
            ->add('message', TextareaType::class, [
                'label' => 'label.message',
                'required' => true
            ]);

    }
    public function getName()
    {
        return 'Contact';
    }
}




