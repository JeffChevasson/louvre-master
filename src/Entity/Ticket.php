<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 * @ORM\Table(name="ticket")
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotNull()
     */
    private $lastname;

    /**
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotNull()
     */
    private $firstname;

    /**
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotNull()
     */
    private $country;

    /**
     * @ORM\Column(name="birthdate", type="datetime")
     * @Assert\NotNull()
     */
    private $birthdate;

    /**
     * @ORM\Column(name="discount", type="boolean")
     * @Assert\NotNull()
     */
    private $discount;

    /**
     * @ORM\Column(name="price", type="float")
     * @Assert\NotNull()
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getDiscount(): ?bool
    {
        return $this->discount;
    }

    public function setDiscount(bool $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
    public function setVisit(Visit $visit)
    {
        $this->visit = $visit;
        return $this;
     }

    /**
     * @return mixed
     */
    public function getVisit()
     {
         return $this->visit;
     }
}
