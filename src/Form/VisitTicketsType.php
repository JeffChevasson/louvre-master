<?php

namespace App\Form;



use App\Entity\Ticket;
use App\Entity\Visit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitTicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tickets', CollectionType::class,[
                'label' => null,
                'entry_options'=> [
                    'label' =>null
                ],
                'entry_type' => TicketType::class,
                'by_reference' =>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visit::class,
            'label' => null
        ]);
    }
}


