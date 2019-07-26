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
     * @Assert\NotBlank(message="Vous devez saisir un nom de famille")
     * @Assert\Regex(
     *     pattern="/[-a-zA-Zéèàêâùïüë]/",
     *     message="Le nom saisi est incorrect")
     */
    private $lastname;

    /**
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un prénom")
     * @Assert\Regex(
     *     pattern="/[-a-zA-Zéèàêâùïüë]/",
     *     message="Le prénom saisi est incorrect")
     */
    private $firstname;

    /**
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @ORM\Column(name="birthdate", type="datetime")
     * @Assert\LessThan("today")
     * @Assert\Date()
     */
    private $birthdate;

    /**
     * @ORM\Column(name="discount", type="boolean")
     */
    private $discount;

    /**
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Visit", inversedBy="tickets",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $visit;


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

    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTime $birthdate): self
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


    public function getVisit(): ?Visit
    {
        return $this->visit;
    }

    public function setVisit(?Visit $visit): self
    {
        $this->visit = $visit;

        return $this;
    }

}
