<?php


namespace App\Validator\Constraints;

use App\Entity\Visit;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class OverLimitSoldTicketsValidator extends ConstraintValidator

{

    /**
     * @var VisitRepository
     */
    private $visitRepository;

    /**
     * OneThousandTicketsValidator constructor.
     * @param VisitRepository $visitRepository
     */
    public function __construct(VisitRepository $visitRepository)
    {

        $this->visitRepository = $visitRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws NonUniqueResultException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof OverLimitSoldTickets)
        {
            return;
        }

        if(!$value instanceof Visit)
        {
            return;
        }

        $visitDate = $value->getVisiteDate ();

        $total = $this->visitRepository->countNbTicketsOnThisDate($visitDate);

        if ($total + $value->getNbTicket () > $constraint->nbTicketsByDay)
        {
            $this->context->buildViolation ($constraint->getMessage ())
                ->atPath ('nbTicket')
                ->addViolation ();
        }
    }
}