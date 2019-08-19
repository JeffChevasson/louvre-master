<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;



class NoReservationOnDayValidator extends ConstraintValidator
{
    public function check($day)
    {
        if ($day->format('w') != 0)
        {
            return false;
        }
        elseif ($day->format('w') != 2)
        {
            return false;
        }

        else
        {
            return true;
        }
    }

    public function validate($value, Constraint $constraint)
    {
        if ($day->format('w') == $constraint->day)
        {
            $this->context->buildViolation ($constraint->getMessage())
                ->addViolation ();
        }
    }
}