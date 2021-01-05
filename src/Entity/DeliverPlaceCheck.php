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
     * @ORM\ManyToOne(targetEntity=DeliveryPlace::class, inversedBy="deliverPlaceChecks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deliveryPlace;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $versorger;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sended;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sendedAt;


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

    public function getDeliveryPlace(): ?DeliveryPlace
    {
        return $this->deliveryPlace;
    }

    public function setDeliveryPlace(?DeliveryPlace $deliveryPlace): self
    {
        $this->deliveryPlace = $deliveryPlace;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getVersorger(): ?int
    {
        return $this->versorger;
    }

    public function setVersorger(?int $versorger): self
    {
        $this->versorger = $versorger;

        return $this;
    }

    public function getSended(): ?bool
    {
        return $this->sended;
    }

    public function setSended(?bool $sended): self
    {
        $this->sended = $sended;

        return $this;
    }

    public function getSendedAt(): ?\DateTimeInterface
    {
        return $this->sendedAt;
    }

    public function setSendedAt(?\DateTimeInterface $sendedAt): self
    {
        $this->sendedAt = $sendedAt;

        return $this;
    }

}
