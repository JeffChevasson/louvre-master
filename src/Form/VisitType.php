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
                'label' => 'Date de votre visite :',
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
                'expanded' => false,
                'multiple' => false,
                'required' => true
            ])
            ->add('nbticket', ChoiceType::class, [
                'choices' => [
                    '1' => 0, '2' => 1, '3' => 2, '4' => 3, '5' => 4, '6' => 5, '7' => 6, '8' => 7, '9' => 10, '10' => 9,
                    '11' => 10, '12' => 11, '13' => 12, '14' => 13, '15' => 14, '16' => 15, '17' => 16, '18' => 17,
                    '19' => 18, '20' => 19
                ],
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
