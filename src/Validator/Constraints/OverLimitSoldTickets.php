<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class OneThousandTickets
 * @package App\Validator\Constraints
 * @Annotation
 */

class OverLimitSoldTickets extends Constraint
{
    public $nbTicketsByDay;

   public function getRequiredOptions()
    {
        return [
          'nbTicketsByDay'
        ];
    }

    public function getMessage()
    {
        return 'Il n\' y a plus de billet disponible à cette date';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}