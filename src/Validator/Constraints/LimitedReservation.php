<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;




/**
* @Annotation
*/
class LimitedReservation extends Constraint
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
        return 'une fois %hour% passé vous ne pouvez plus achetez de billet de type "journée"';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}