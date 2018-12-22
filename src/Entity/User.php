<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Asserts;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Asserts\NotBlank(message="First name must be provided")
     * @Asserts\Length(max=100, maxMessage="First name must have 100 characters in maximum")
     * @ORM\Column(type="string", length=100)
     */
    private $firstname;

    /**
     * @Asserts\NotBlank(message="Last name must be provided")
     * @Asserts\Length(max=100, maxMessage="Last name must have 100 characters in maximum")
     * @ORM\Column(type="string", length=100)
     */
    private $lastname;

    /**
     * @Asserts\NotBlank(message="Email must be provided")
     * @Asserts\Email(message="Email must be valid")
     * @ORM\Column(type="string", length=200, unique=true)
     */
    private $email;

    /**
     * @Asserts\NotBlank(message="Phone number must be provided")
     * @AssertPhoneNumber(message="Phone number must be valid")
     * @ORM\Column(type="string", length=35, unique=true)
     */
    private $phonenumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $postalcode;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $city;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Exclude
     */
    private $dateCreate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BusinessCustomer", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Serializer\Exclude
     */
    private $BusinessCustomer;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phonenumber;
    }

    /**
     * @param mixed $phonenumber
     */
    public function setPhoneNumber($phonenumber): void
    {
        $this->phonenumber = $phonenumber;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPostalCode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalCode(string $postalcode): self
    {
        $this->postalcode = $postalcode;

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

    public function getDateCreate(): string
    {
        return $this->dateCreate->format('Y-m-d H:i:s');
    }

    public function setDateCreate(\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getBusinessCustomer(): ?BusinessCustomer
    {
        return $this->BusinessCustomer;
    }

    public function setBusinessCustomer(?BusinessCustomer $BusinessCustomer): self
    {
        $this->BusinessCustomer = $BusinessCustomer;

        return $this;
    }
}
