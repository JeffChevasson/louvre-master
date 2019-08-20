<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as LouvreAssert;





/**
 * @ORM\Entity(repositoryClass="App\Repository\VisitRepository")
 * @ORM\Table(name="visit")
 * @LouvreAssert\LimitedReservation(hour=14)
 *
 */


class Visit
{
    const TYPE_FULL_DAY = 0;
    const TYPE_HALF_DAY = 1;
    const NB_TICKET_MAX_DAY = 1000;

    const LIMITED_HOUR_TODAY = 16;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     *
     *
     */
    private $id;

    /**
     * @ORM\Column(name="invoicedate", type="datetime")
     * @Assert\DateTime()
     */
    private $invoiceDate;

    /**
     *
     * @ORM\Column(name="visitedate", type="datetime")
     * @Assert\Range(
     *     min = "today",
     *     max = "+1 year",
     *     minMessage = "Vous devez choisir une date de visite supérieure ou égale à la date du jour",
     *     maxMessage = "La réservation est uniquement sur l'année en cours")
     * @LouvreAssert\NoReservationOnPublicHolidays()
     * @LouvreAssert\NoReservationOnDay(day="0")
     * @LouvreAssert\NoReservationOnDay(day="2")
     * @LouvreAssert\ToLateForToday(hour=16)
     * @Assert\DateTime()

     */
    private $visiteDate;

    /**
     * @ORM\Column(name="type", type="integer")
     * @Assert\NotBlank
     *

     */
    private $type;

    /**
     * @ORM\Column(name="nbticket", type="integer")
     * @Assert\NotNull()
     * @Assert\Range(
     *      min = 1,
     *      max = 20,
     *      minMessage = "vous devez saisir au moins 1 ticket",
     *      maxMessage = "vous ne pouvez pas commander plus de 20 tickets"
     * )
     */
    private $nbTicket;


    /**
     * @ORM\Column(name="totalamount", type="integer")
     *
     */
    private $totalAmount;


    /**
     * @ORM\Column(name="bookingcode", type="integer", unique=true)
     *
     */
    private $bookingCode;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $customer;


    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="visit",cascade={"persist"})

     *
     */
    private $tickets;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoiceDate;
    }

    public function setInvoiceDate(\DateTimeInterface $invoicedate): self
    {
        $this->invoiceDate = $invoicedate;

        return $this;
    }

    public function getVisiteDate(): ?\DateTimeInterface
    {
        return $this->visiteDate;
    }

    public function setVisiteDate(\DateTimeInterface $visitedate): self
    {
        $this->visiteDate = $visitedate;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNbTicket(): ?int
    {
        return $this->nbTicket;
    }

    public function setNbTicket(int $nbticket): self
    {
        $this->nbTicket = $nbticket;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalamount): self
    {
        $this->totalAmount = $totalamount;

        return $this;
    }

    public function getBookingCode(): ?string
    {
        return $this->bookingCode;
    }

    public function setBookingCode($bookingcode) : self
    {
        $this->bookingCode = $bookingcode;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTickets(): ArrayCollection
    {
        return $this->tickets;
    }

    /**
     * @param Ticket $ticket
     */
    public function addTicket(Ticket $ticket)
    {
        if (!$this->tickets->contains($ticket)) {

            $this->tickets->add($ticket);
            $ticket->setVisit ($this);
        }
    }

    public function removeTicket(Ticket $ticket)
    {
        if ($this->tickets->contains($ticket)) {

            $this->tickets->remove($ticket);
        }
    }

    /**
     * Visit constructor.
     */
    public function __construct()
    {
        $this->setInvoiceDate(new \DateTime());
        $this->tickets=new ArrayCollection();
    }

    /**
     * Set customer.
     *
     * @param Customer $customer
     *
     * @return Visit
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }
    /**
     * Get customer.
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
