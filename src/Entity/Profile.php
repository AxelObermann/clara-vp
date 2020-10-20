<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 */
class Profile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Firma;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Steuernummer;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Finanzamt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $iban;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $bic;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Bank;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $telefon;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $plz;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $ort;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $strassenr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $signatur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirma(): ?string
    {
        return $this->Firma;
    }

    public function setFirma(?string $Firma): self
    {
        $this->Firma = $Firma;

        return $this;
    }

    public function getSteuernummer(): ?string
    {
        return $this->Steuernummer;
    }

    public function setSteuernummer(?string $Steuernummer): self
    {
        $this->Steuernummer = $Steuernummer;

        return $this;
    }

    public function getFinanzamt(): ?string
    {
        return $this->Finanzamt;
    }

    public function setFinanzamt(?string $Finanzamt): self
    {
        $this->Finanzamt = $Finanzamt;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getBic(): ?string
    {
        return $this->bic;
    }

    public function setBic(?string $bic): self
    {
        $this->bic = $bic;

        return $this;
    }

    public function getBank(): ?string
    {
        return $this->Bank;
    }

    public function setBank(?string $Bank): self
    {
        $this->Bank = $Bank;

        return $this;
    }

    public function getTelefon(): ?string
    {
        return $this->telefon;
    }

    public function setTelefon(?string $telefon): self
    {
        $this->telefon = $telefon;

        return $this;
    }

    public function getPlz(): ?string
    {
        return $this->plz;
    }

    public function setPlz(?string $plz): self
    {
        $this->plz = $plz;

        return $this;
    }

    public function getOrt(): ?string
    {
        return $this->ort;
    }

    public function setOrt(?string $ort): self
    {
        $this->ort = $ort;

        return $this;
    }

    public function getStrassenr(): ?string
    {
        return $this->strassenr;
    }

    public function setStrassenr(?string $strassenr): self
    {
        $this->strassenr = $strassenr;

        return $this;
    }

    public function getSignatur(): ?string
    {
        return $this->signatur;
    }

    public function setSignatur(?string $signatur): self
    {
        $this->signatur = $signatur;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
