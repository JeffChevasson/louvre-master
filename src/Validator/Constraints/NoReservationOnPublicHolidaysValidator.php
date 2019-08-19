<?php


namespace App\Validator\Constraints;

use App\Services\PublicHolidaysService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoReservationOnPublicHolidaysValidator extends ConstraintValidator
{
    private $publicHolidaysService;



    public function __construct(PublicHolidaysService $publicHolidaysService)
    {
        $this->publicHolidaysService = $publicHolidaysService;
    }

    public function validate($value, Constraint $constraint)
    {

        if ($this->publicHolidaysService->checkIsHoliday ($value) === true)
        {
            $this->context->buildViolation ($constraint->getMessage())
                ->addViolation ();
        }
    }
}