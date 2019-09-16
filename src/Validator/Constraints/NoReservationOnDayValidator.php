<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;



class NoReservationOnDayValidator extends ConstraintValidator
{
    /**
     * @param $day
     * @param Constraint $constraint
     */
    public function validate($day, Constraint $constraint)
    {


        if ($day->format('w') == $constraint->day)
        {
            $this->context->buildViolation ($constraint->getMessage())
                ->addViolation ();
        }
    }
}