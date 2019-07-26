<?php


namespace App\Validators\Constraints;


use Symfony\Component\Validator\Constraint;



class NoReservationOnPublicHolidays extends Constraint
{
    public $publicHolidays;

    public function __construct($options = null)
    {
        Parent::__construct($options);
    }

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
}