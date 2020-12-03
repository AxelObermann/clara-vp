<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $displayName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $registrationCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=Profile::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $profile;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $oldid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $provstufe;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity=Calendar::class, mappedBy="user", orphanRemoval=true)
     */
    private $calendars;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parentID;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="toUser")
     */
    private $notifications;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $favorite = [];

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="User")
     */
    private $customers;

    /**
     * @ORM\OneToOne(targetEntity=DeliverPlaceCheck::class, mappedBy="assignedTo", cascade={"persist", "remove"})
     */
    private $deliverPlaceCheck;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $confirmed;

    /**
     * @ORM\OneToMany(targetEntity=DeliveryPlace::class, mappedBy="facilityUser")
     */
    private $deliveryPlaces;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $allowedCustomer = [];


    public function __construct()
    {
        $this->calendars = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->customers = new ArrayCollection();
        $this->deliveryPlaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getRegistrationCode(): ?string
    {
        return $this->registrationCode;
    }

    public function setRegistrationCode(?string $registrationCode): self
    {
        $this->registrationCode = $registrationCode;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;

        // set the owning side of the relation if necessary
        if ($profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        return $this;
    }

    public function getOldid(): ?string
    {
        return $this->oldid;
    }

    public function setOldid(?string $oldid): self
    {
        $this->oldid = $oldid;

        return $this;
    }

    public function getProvstufe(): ?int
    {
        return $this->provstufe;
    }

    public function setProvstufe(?int $provstufe): self
    {
        $this->provstufe = $provstufe;

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

    /**
     * @return Collection|Calendar[]
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): self
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars[] = $calendar;
            $calendar->setUser($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): self
    {
        if ($this->calendars->contains($calendar)) {
            $this->calendars->removeElement($calendar);
            // set the owning side to null (unless already changed)
            if ($calendar->getUser() === $this) {
                $calendar->setUser(null);
            }
        }

        return $this;
    }

    public function getParentID(): ?int
    {
        return $this->parentID;
    }

    public function setParentID(?int $parentID): self
    {
        $this->parentID = $parentID;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setToUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getToUser() === $this) {
                $notification->setToUser(null);
            }
        }

        return $this;
    }

    public function getFavorite(): ?array
    {
        return $this->favorite;
    }

    public function setFavorite(?array $favorite): self
    {
        $this->favorite = $favorite;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setUser($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getUser() === $this) {
                $customer->setUser(null);
            }
        }

        return $this;
    }

    public function getDeliverPlaceCheck(): ?DeliverPlaceCheck
    {
        return $this->deliverPlaceCheck;
    }

    public function setDeliverPlaceCheck(?DeliverPlaceCheck $deliverPlaceCheck): self
    {
        $this->deliverPlaceCheck = $deliverPlaceCheck;

        // set (or unset) the owning side of the relation if necessary
        $newAssignedTo = null === $deliverPlaceCheck ? null : $this;
        if ($deliverPlaceCheck->getAssignedTo() !== $newAssignedTo) {
            $deliverPlaceCheck->setAssignedTo($newAssignedTo);
        }

        return $this;
    }

    public function getConfirmed(): ?bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(?bool $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
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
            $deliveryPlace->setFacilityUser($this);
        }

        return $this;
    }

    public function removeDeliveryPlace(DeliveryPlace $deliveryPlace): self
    {
        if ($this->deliveryPlaces->removeElement($deliveryPlace)) {
            // set the owning side to null (unless already changed)
            if ($deliveryPlace->getFacilityUser() === $this) {
                $deliveryPlace->setFacilityUser(null);
            }
        }

        return $this;
    }

    public function getAllowedCustomer(): ?array
    {
        return $this->allowedCustomer;
    }

    public function setAllowedCustomer(?array $allowedCustomer): self
    {
        $this->allowedCustomer = $allowedCustomer;

        return $this;
    }

}
