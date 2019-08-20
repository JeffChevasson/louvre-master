<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class NoReservationOnSunday
 * @package App\Validator\Constraints
 * @Annotation
 */

class NoReservationOnDay extends Constraint
{
    public $day;

   public function getRequiredOptions()
    {
        return [
            'day'
        ];
    }

    public function getMessage()
    {
        return 'Il n\' est pas possible de réserver un billet sur un jour de fermeture du musée';
    }

}