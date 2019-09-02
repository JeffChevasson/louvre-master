<?php

namespace App\Manager;


use App\Entity\Prices;
use App\Services\EmailService;
use App\Services\PriceCalculator;
use App\Services\PublicHolidaysService;
use App\Entity\Ticket;
use App\Entity\Visit;
use App\Exception\InvalidVisitSessionException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class VisitManager
{
    const SESSION_ID_CURRENT_VISIT = "visit";
    /**
     *
     * @var SessionInterface
     */
    private $session;
    private $publicHolidaysService;
    private $validator;
    private $emailService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PriceCalculator
     */
    private $priceCalculator;

    public function __construct(SessionInterface $session, PublicHolidaysService $publicHolidaysService, ValidatorInterface $validator,
                                EmailService $emailService, EntityManagerInterface $entityManager, PriceCalculator $priceCalculator)
    {
        $this->session = $session;
        $this->publicHolidaysService = $publicHolidaysService;
        $this->validator = $validator;
        $this->emailService = $emailService;
        $this->entityManager = $entityManager;
        $this->priceCalculator = $priceCalculator;
    }
    /**
     * Page 2
     * Initialisation de la Visit et de la Session
     * Création de l'objet Visit
     * @return Visit
     */
    public function initVisit()
    {
        $visit = new Visit();
        $this->whichVisitDay($visit);
        $this->session->set(self::SESSION_ID_CURRENT_VISIT,$visit);
        return $visit;
    }
    public function whichVisitDay(Visit $visit)
    {
        date_default_timezone_set('Europe/Paris');
        $hour = date("H");
        $today = date("w");
        $tomorrow = date('w', strtotime('+1 day'));
        $publicHolidays = $this->publicHolidaysService->getPublicHolidaysOfThisYear();
        if($hour > Visit::LIMITED_HOUR_TODAY || $today == 0 || $today == 2 || $today == $publicHolidays)
        {
            $visiteDate = (new DateTime())->modify('+ 1 days');
            if($tomorrow  == 0 || $tomorrow == 2 || $tomorrow == $publicHolidays)
            {
                $visiteDate = (new DateTime())->modify('+ 2 days');
            }
        }
        else
            {
            $visiteDate = (new DateTime());
            }
        $visit->setVisiteDate($visiteDate);
        return $visiteDate;
    }
    /**
     *
     * Retourne la visite en cours dans la session
     * @param null $validateBy
     * @return Visit
     * @throws InvalidVisitSessionException $validateBy sert à appeler la constante de classe visit
     */
    public function getCurrentVisit($validateBy = null)
    {
        $visit = $this->session->get(self::SESSION_ID_CURRENT_VISIT);
        if(!$visit instanceof Visit)
        {
            throw new InvalidVisitSessionException("Cette page est inaccessible.");
        }
        if(!empty($validateBy) && count($this->validator->validate($visit,null,$validateBy)) > 0)
        {
            throw new InvalidVisitSessionException("Commande invalide.");
        }
        return $visit;
    }

    public function cleanCurrentVisit()
    {
        $this->session->remove (self::SESSION_ID_CURRENT_VISIT);

    }


    /**
     * @param Visit $visit
     * @return int
     * @throws Exception
     */
    public function computePrice(Visit $visit)
    {
        $totalAmount = 0;

        foreach ($visit->getTickets() as $ticket)
            {
                $priceTicket = $this->priceCalculator->computeTicketPrice($ticket);
                dump($priceTicket);
                $totalAmount += $priceTicket;
            }
        $visit->setTotalAmount($totalAmount);
        return $totalAmount;
    }

    /**
     * @param Visit $visit
     * @return Visit
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function postPaymentAction(Visit $visit)
    {
        $bookingCode = md5(uniqid(rand(), true));
        $visit->setBookingCode($bookingCode);
        $this->emailService->sendMailConfirmation($visit);

        $this->entityManager->persist ($visit);
        $this->entityManager->flush ();
        return $visit;
    }


    /**
     * @param EmailService $sendMailContact
     */
    public function generateMailContact(EmailService $sendMailContact)
    {
        try
            {
            $this->emailService->sendMailContact ($sendMailContact);
            }
        catch (LoaderError $e) {}
        catch (RuntimeError $e) {}
        catch (SyntaxError $e) {}
    }

    public function generateEmptyTickets(Visit $visit)
    {

        for ($i = 1; $i <= $visit->getNbTicket (); $i++)
        {
            $visit->addTicket (new Ticket());
        }
    }


}