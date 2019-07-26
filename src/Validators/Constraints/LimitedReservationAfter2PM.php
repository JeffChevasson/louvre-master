<?php


namespace App\Validators\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\Response;

class LimitedReservationAfter2PM extends Constraint
{
    public $hour;
    public function __construct($options = null)
    {
        parent::__construct ($options);
    }

    public function getRequiredOptions()
    {
        return [
            'hour'
        ];
    }

    public function getMessage()
    {
        return 'une fois %hour% passé vous ne pouvez plus achetez de billet de type "journée"';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}