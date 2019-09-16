<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un prénom")
     * @Assert\Regex(
     *     pattern="/[-a-zA-Zéèàêâùïüë]/",
     *     message="Le prénom saisi est incorrect")
     */
    private $firstname;

    /**
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un nom de famille")
     * @Assert\Regex(
     *     pattern="/[-a-zA-Zéèàêâùïüë]/",
     *     message="Le nom saisi est incorrect")
     */
    private $lastname;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un email valide")
     * @Assert\Email(
     *     strict=true,
     *     message="L'email saisi n'est pas valide")
     */
    private $email;

    /**
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir une adresse")
     */
    private $address;

    /**
     * @ORM\Column(name="postcode", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un code postal")
     */
    private $postcode;

    /**
     * @ORM\Column(name="city", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir une ville")
     */
    private $city;

    /**
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un pays")
     */
    private $country;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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
}
