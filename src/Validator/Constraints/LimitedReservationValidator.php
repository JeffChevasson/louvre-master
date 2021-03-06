<?php


namespace App\Validator\Constraints;

use App\Entity\Visit;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LimitedReservationValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $hour = date('H');


        if (!$constraint instanceof LimitedReservation)
        {
            return;
        }

        if(! $value instanceof Visit)
        {
            return;
        }
        if($value->getType () == Visit::TYPE_FULL_DAY &&
        $hour >= $constraint->hour &&
        $value->getVisiteDate ()->format ('dmY') === date ('dmY'))
        {
            $this->context->buildViolation ($constraint->getMessage())
                ->setParameter ('%hour',$constraint->hour)
                ->atPath ('type')
                ->addViolation ();
        }
    }

}