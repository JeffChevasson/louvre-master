<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class NoReservationOnPublicHolidays
 * @package App\Validator\Constraints
 * @Annotation
 */

class NoReservationOnPublicHolidays extends Constraint
{



    public function getMessage()
    {
        return 'Réservation indisponible, ce jour est férié';
    }

}