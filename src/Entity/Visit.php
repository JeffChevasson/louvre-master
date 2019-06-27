<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VisitRepository")
 * @ORM\Table(name="visit")
 */
class Visit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="invoicedate", type="datetime")
     * @Assert\NotNull()
     */
    private $invoicedate;

    /**
     * @ORM\Column(name="visitedate", type="datetime")
     * @Assert\NotNull()
     */
    private $visitedate;

    /**
     * @ORM\Column(name="type", type="integer")
     * @Assert\NotNull()
     * @Assert\NotNull()
     */
    private $type;

    /**
     * @ORM\Column(name="nbticket", type="integer")
     * @Assert\NotNull()
     */
    private $nbticket;

    /**
     * @ORM\Column(name="totalamount", type="integer")
     * @Assert\NotNull()
     */
    private $totalamount;

    /**
     * @ORM\Column(name="bookingcode", type="integer")
     * @Assert\NotNull()
     */
    private $bookingcode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoicedate(): ?\DateTimeInterface
    {
        return $this->invoicedate;
    }

    public function setInvoicedate(\DateTimeInterface $invoicedate): self
    {
        $this->invoicedate = $invoicedate;

        return $this;
    }

    public function getVisitedate(): ?\DateTimeInterface
    {
        return $this->visitedate;
    }

    public function setVisitedate(\DateTimeInterface $visitedate): self
    {
        $this->visitedate = $visitedate;

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

    public function getNbticket(): ?int
    {
        return $this->nbticket;
    }

    public function setNbticket(int $nbticket): self
    {
        $this->nbticket = $nbticket;

        return $this;
    }

    public function getTotalamount(): ?int
    {
        return $this->totalamount;
    }

    public function setTotalamount(int $totalamount): self
    {
        $this->totalamount = $totalamount;

        return $this;
    }

    public function getBookingcode(): ?int
    {
        return $this->bookingcode;
    }

    public function setBookingcode(int $bookingcode): self
    {
        $this->bookingcode = $bookingcode;

        return $this;
    }
}
