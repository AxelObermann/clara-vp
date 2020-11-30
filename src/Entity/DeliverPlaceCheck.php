<?php

namespace App\Entity;

use App\Repository\DeliverPlaceCheckRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeliverPlaceCheckRepository::class)
 */
class DeliverPlaceCheck
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $datum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wert;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="deliverPlaceCheck", cascade={"persist", "remove"})
     */
    private $assignedTo;

    /**
     * @ORM\ManyToOne(targetEntity=DeliveryPlace::class, inversedBy="deliverPlaceChecks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deliveryPlace;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatum(): ?\DateTimeInterface
    {
        return $this->datum;
    }

    public function setDatum(\DateTimeInterface $datum): self
    {
        $this->datum = $datum;

        return $this;
    }

    public function getWert(): ?string
    {
        return $this->wert;
    }

    public function setWert(string $wert): self
    {
        $this->wert = $wert;

        return $this;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?User $assignedTo): self
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    public function getDeliveryPlace(): ?DeliveryPlace
    {
        return $this->deliveryPlace;
    }

    public function setDeliveryPlace(?DeliveryPlace $deliveryPlace): self
    {
        $this->deliveryPlace = $deliveryPlace;

        return $this;
    }
}
