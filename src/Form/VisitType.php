<?php

namespace App\Form;

use App\Entity\Visit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class VisitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('visitedate', DateType::class, [
                'label' => 'Date de visite :',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'datepicker'],
                'required' => true
            ]
        )
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Billet journée' => 0,
                    'Billet demi-journée (à partir de 14h)' => 1
                ],
                'label' => 'Types de billets :',
                'expanded' => true,
                'multiple' => false,
                'required' => true
            ])
            ->add('nbticket', ChoiceType::class, [
                'choices' => array_combine(\range(1,20), \range(1,20)),
                'label' => 'Nombre de billets :',
                'required' => true
            ]);
           // ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visit::class,
            'validation_groups' => ['init']
        ]);
    }
}
