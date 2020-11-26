<?php

namespace App\Entity;

use App\Repository\CalendarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarRepository::class)
 */
class Calendar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $oldId;

    /**
     * @ORM\Column(type="string", length=145, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allday;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $customerName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $streetNumber;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $plz;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ort;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ansprechpartner;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $anrede;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bermerkungen;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $terminType;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="calendars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldId(): ?int
    {
        return $this->oldId;
    }

    public function setOldId(?int $oldId): self
    {
        $this->oldId = $oldId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getAllday(): ?bool
    {
        return $this->allday;
    }

    public function setAllday(?bool $allday): self
    {
        $this->allday = $allday;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): self
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?string $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

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

    public function getAnsprechpartner(): ?string
    {
        return $this->ansprechpartner;
    }

    public function setAnsprechpartner(?string $ansprechpartner): self
    {
        $this->ansprechpartner = $ansprechpartner;

        return $this;
    }

    public function getAnrede(): ?string
    {
        return $this->anrede;
    }

    public function setAnrede(?string $anrede): self
    {
        $this->anrede = $anrede;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBermerkungen(): ?string
    {
        return $this->bermerkungen;
    }

    public function setBermerkungen(?string $bermerkungen): self
    {
        $this->bermerkungen = $bermerkungen;

        return $this;
    }

    public function getTerminType(): ?string
    {
        return $this->terminType;
    }

    public function setTerminType(?string $terminType): self
    {
        $this->terminType = $terminType;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
