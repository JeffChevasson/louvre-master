<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ToLateForToday
 * @package App\Validator\Constraints
 * @Annotation
 */


class ToLateForToday extends Constraint
{
    public $hour;

    public function getRequiredOptions()
    {
        return [
            'hour'
        ];
    }

    public function getMessage()
    {
        return 'Une fois %hour% passées, vous ne pouvez plus effectuer une réservation sur le jour en cours';
    }
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}