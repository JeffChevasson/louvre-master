<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;



class NoReservationOnDayValidator extends ConstraintValidator
{
    /**
     * @param $day
     * @return bool
     */
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

    /**
     * @param $day
     * @param Constraint $constraint
     */
    public function validate($day, Constraint $constraint)
    {


        if ($day->format('w') == false)
        {
            $this->context->buildViolation ($constraint->getMessage())
                ->addViolation ();
        }
    }
}