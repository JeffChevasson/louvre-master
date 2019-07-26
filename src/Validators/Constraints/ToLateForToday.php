<?php


namespace App\Validators\Constraints;

use Symfony\Component\Validator\Constraint;



class ToLateForToday extends Constraint
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
        return 'Une fois %hour%h passées, vous ne pouvez plus effectuer une réservation sur le jour en cours';
    }

}