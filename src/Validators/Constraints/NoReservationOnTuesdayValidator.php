<?php


namespace App\Validators\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoReservationOnTuesdayValidator extends ConstraintValidator
{
    public function check($day)
    {
        if ($day->format('w') == 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function validate($value, Constraint $constraint)
    {
        if ($this->check ($value) == true)
        {
            $this->context->buildViolation ($constraint->getMessage())
                ->addViolation ();
        }
    }
}