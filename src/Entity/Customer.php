<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactPerson;

    /**
     * @ORM\OneToMany(targetEntity=Adress::class, mappedBy="Adress")
     */
    private $adress;

    /**
     * @ORM\Column(type="integer")
     */
    private $oldId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\OneToMany(targetEntity=Adress::class, mappedBy="Customer")
     */
    private $adresses;

    /**
     * @ORM\OneToMany(targetEntity=DeliveryPlace::class, mappedBy="customer")
     */
    private $deliveryPlaces;

    public function __construct()
    {
        $this->adress = new ArrayCollection();
        $this->adresses = new ArrayCollection();
        $this->deliveryPlaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getContactPerson(): ?string
    {
        return $this->contactPerson;
    }

    public function setContactPerson(?string $contactPerson): self
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    /**
     * @return Collection|Adress[]
     */
    public function getAdress(): Collection
    {
        return $this->adress;
    }

    public function addAdress(Adress $adress): self
    {
        if (!$this->adress->contains($adress)) {
            $this->adress[] = $adress;
            $adress->setAdress($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): self
    {
        if ($this->adress->contains($adress)) {
            $this->adress->removeElement($adress);
            // set the owning side to null (unless already changed)
            if ($adress->getAdress() === $this) {
                $adress->setAdress(null);
            }
        }

        return $this;
    }

    public function getOldId(): ?int
    {
        return $this->oldId;
    }

    public function setOldId(int $oldId): self
    {
        $this->oldId = $oldId;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection|Adress[]
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    /**
     * @return Collection|DeliveryPlace[]
     */
    public function getDeliveryPlaces(): Collection
    {
        return $this->deliveryPlaces;
    }

    public function addDeliveryPlace(DeliveryPlace $deliveryPlace): self
    {
        if (!$this->deliveryPlaces->contains($deliveryPlace)) {
            $this->deliveryPlaces[] = $deliveryPlace;
            $deliveryPlace->setCustomer($this);
        }

        return $this;
    }

    public function removeDeliveryPlace(DeliveryPlace $deliveryPlace): self
    {
        if ($this->deliveryPlaces->contains($deliveryPlace)) {
            $this->deliveryPlaces->removeElement($deliveryPlace);
            // set the owning side to null (unless already changed)
            if ($deliveryPlace->getCustomer() === $this) {
                $deliveryPlace->setCustomer(null);
            }
        }

        return $this;
    }
}
