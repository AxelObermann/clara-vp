<?php

namespace App\Entity;

use App\Repository\UploadedFilesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UploadedFilesRepository::class)
 */
class UploadedFiles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="uploadedFiles")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="uploadedFiles")
     */
    private $Customer;

    /**
     * @ORM\ManyToOne(targetEntity=DeliveryPlace::class, inversedBy="uploadedFiles")
     */
    private $DeliveryPlace;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploaded;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->Customer;
    }

    public function setCustomer(?Customer $Customer): self
    {
        $this->Customer = $Customer;

        return $this;
    }

    public function getDeliveryPlace(): ?DeliveryPlace
    {
        return $this->DeliveryPlace;
    }

    public function setDeliveryPlace(?DeliveryPlace $DeliveryPlace): self
    {
        $this->DeliveryPlace = $DeliveryPlace;

        return $this;
    }

    public function getUploaded(): ?\DateTimeInterface
    {
        return $this->uploaded;
    }

    public function setUploaded(\DateTimeInterface $uploaded): self
    {
        $this->uploaded = $uploaded;

        return $this;
    }
}
