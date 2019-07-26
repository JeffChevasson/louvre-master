<?php


namespace App\Validators\Constraints;

use Symfony\Component\Validator\Constraint;

class NoReservationOnSunday extends Constraint
{
    public $day;

    public function __construct($options = null)
    {
        parent::__construct ($options);
    }

    public function getRequiredOptions()
    {
        return [
            'day'
        ];
    }

    public function getMessage()
    {
        return 'Il n\' est pas possible de réserver un billet sur la journée de dimanche';
    }
}