<?php


namespace App\Validators\Constraints;

use Symfony\Component\Validator\Constraint;

class OneThousandTickets extends Constraint
{
    public $nbTicketsByDay;

    public function __construct($options = null)
    {
        parent::__construct ($options);
    }

    public function getRequiredOptions()
    {
        return [
          'nbTicketByDay'
        ];
    }

    public function getMessage()
    {
        return 'Il n\' y a plus de billet disponible à cette date';
    }
}