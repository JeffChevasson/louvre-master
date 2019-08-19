<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class ToLateForTodayValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {

        if (!$value instanceof \DateTime) return;

    $hour = Date ("H");

    if ($value->format ('dmY') === date ('dmY') && $hour >= $constraint->hour) {
        $this->context->buildViolation ($constraint->getMessage())
            ->setParameter ('%hour%', $constraint->hour)
            ->addViolation ();
    }

    }
}
