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
    public $publicHolidays;



    public function getRequiredOptions()
    {
        return [
            'publicHolidays'
        ];
    }

    public function getMessage()
    {
        return 'Réservation indisponible, ce jour est férié';
    }
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}