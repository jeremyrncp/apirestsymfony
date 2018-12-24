<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $memory;

    /**
     * @ORM\Column(type="integer")
     */
    private $warranty;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ScreenSize", inversedBy="phones")
     * @ORM\JoinColumn(nullable=false)
     *
     *
     * @Serializer\SerializedName("screensize")
     * @Serializer\Type("string")
     */
    private $ScreenSize;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Manufacturer", inversedBy="phones")
     * @ORM\JoinColumn(nullable=false)
     *
     *
     * @Serializer\SerializedName("manufacturer")
     * @Serializer\Type("string")
     */
    private $Manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhoneCategory", inversedBy="phones")
     * @ORM\JoinColumn(nullable=false)
     *
     *
     * @Serializer\SerializedName("category")
     * @Serializer\Type("string")
     */
    private $Category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Os", inversedBy="phones")
     * @ORM\JoinColumn(nullable=false)
     *
     *
     * @Serializer\SerializedName("os")
     * @Serializer\Type("string")
     */
    private $Os;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Connexion")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Serializer\SerializedName("connexions")
     * @Serializer\Type("array<string>")
     */
    private $Connexions;

    /**
     * @ORM\Column(type="text")
     */
    private $description;



    public function __construct()
    {
        $this->Connexions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(int $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getWarranty(): ?int
    {
        return $this->warranty;
    }

    public function setWarranty(int $warranty): self
    {
        $this->warranty = $warranty;

        return $this;
    }

    public function getScreenSize(): ?ScreenSize
    {
        return $this->ScreenSize;
    }

    public function setScreenSize(?ScreenSize $ScreenSize): self
    {
        $this->ScreenSize = $ScreenSize;

        return $this;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->Manufacturer;
    }

    public function setManufacturer(?Manufacturer $Manufacturer): self
    {
        $this->Manufacturer = $Manufacturer;

        return $this;
    }

    public function getCategory(): ?PhoneCategory
    {
        return $this->Category;
    }

    public function setCategory(?PhoneCategory $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getOs(): ?Os
    {
        return $this->Os;
    }

    public function setOs(?Os $Os): self
    {
        $this->Os = $Os;

        return $this;
    }

    public function addConnexion(Connexion $connexion)
    {
        $this->Connexions->add($connexion);
    }

    public function getConnexion()
    {
        return $this->Connexions;
    }

    public function removeConnexion(Connexion $connexion)
    {
        $this->Connexions->remove($connexion);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
