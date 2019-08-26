<?php


namespace App\Tests\Manager;

use App\Entity\Visit;
use App\Entity\Ticket;
use App\Manager\VisitManager;
use PHPUnit\Framework\TestCase;


class VisitManagerTest extends TestCase
{
    public function createTicket($date, $discount)
    {
        $ticket = new Ticket();
        $birthday = new \DateTime($date);
        $ticket->setBirthdate($birthday);
        $ticket->setDiscount($discount);
        return $ticket;
    }

    public function VisitManagerAndDependenciesMocks()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\SessionInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $publicHolidaysService = $this->getMockBuilder('App\Service\PublicHolidaysService')
            ->disableOriginalConstructor()
            ->getMock();
        $validator = $this->getMockBuilder('Symfony\Component\Validator\Validator\ValidatorInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $emailService = $this->getMockBuilder('App\Service\EmailService')
            ->disableOriginalConstructor()
            ->getMock();
        $visitManager = new VisitManager( $session, $publicHolidaysService, $validator, $emailService);
        return $visitManager;
    }
    public function createOneVisitForSenior()
    {
        $visit = new Visit;
        $date = new \DateTime("28-10-2019");
        $visit->setVisiteDate($date);
        $type = Visit::TYPE_FULL_DAY;
        $visit->setType($type);
        $visit->addTicket($this->createTicket("03-11-1950", 1));
        return $visit;
    }
    public function createOneVisitOnFullDay()
    {
        $visit = new Visit;
        $date = new \DateTime( "28-10-2019" );
        $visit->setVisiteDate ( $date );
        $type = Visit::TYPE_FULL_DAY;
        $visit->setType ( $type );
        $tickets = array(
            $ticket1 = $this->createTicket ( "26-02-1975", 0 ), // 16
            $ticket2 = $this->createTicket ( "26-02-1975", 1 ), // 10
            $ticket3 = $this->createTicket ( "03-11-1950", 0 ), // 12
            $ticket4 = $this->createTicket ( "03-11-1950", 1 ), // 12 no discount for senior
            $ticket5 = $this->createTicket ( "26-11-2010", 0 ), // 8
            $ticket5 = $this->createTicket ( "30-09-2017", 0 ) // 0
        );
        foreach ($tickets as $ticket) {
            $visit->addTicket ( $ticket );
        }
        return $visit;
    }
    public function testComputePrice()
    {
        $visitManager = $this->VisitManagerAndDependenciesMocks();
        $visit = $this->createOneVisitOnFullDay();
        $price = $visitManager->computePrice($visit);
        $this->assertSame(58, $price);
    }
    public function testComputeTicketPrice()
    {
        $visitManager = $this->VisitManagerAndDependenciesMocks();
        $visit = $this->createOneVisitForSenior();
        $price = $visitManager->computeTicketPrice($visit->getTickets()->first());
        $this->assertSame(12, $price);
    }
}