<?php

namespace App\Entity;

use App\Repository\EwGasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EwGasRepository::class)
 */
class EwGas
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
    private $Verbrauchvon;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Verbrauchbis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Tarifgebiet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $VNBGNr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Teilnetznummer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Netzbereichnummer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $TarifkuerzelSWT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $PLZ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Ort;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $NetznutzungskostenArbeitspreis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $NetznutzungskostenGrundpreis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $NetznutzungskostenMessung;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Konzessionsabgabe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Energiearbeitspreis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Energiegrundpreis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Arbeitspreis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ArbeitspreisNT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Grundpreis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Marktgebiet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $VNBCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVerbrauchvon(): ?int
    {
        return $this->Verbrauchvon;
    }

    public function setVerbrauchvon(?int $Verbrauchvon): self
    {
        $this->Verbrauchvon = $Verbrauchvon;

        return $this;
    }

    public function getVerbrauchbis(): ?int
    {
        return $this->Verbrauchbis;
    }

    public function setVerbrauchbis(?int $Verbrauchbis): self
    {
        $this->Verbrauchbis = $Verbrauchbis;

        return $this;
    }

    public function getTarifgebiet(): ?string
    {
        return $this->Tarifgebiet;
    }

    public function setTarifgebiet(?string $Tarifgebiet): self
    {
        $this->Tarifgebiet = $Tarifgebiet;

        return $this;
    }

    public function getVNBGNr(): ?string
    {
        return $this->VNBGNr;
    }

    public function setVNBGNr(?string $VNBGNr): self
    {
        $this->VNBGNr = $VNBGNr;

        return $this;
    }

    public function getTeilnetznummer(): ?string
    {
        return $this->Teilnetznummer;
    }

    public function setTeilnetznummer(?string $Teilnetznummer): self
    {
        $this->Teilnetznummer = $Teilnetznummer;

        return $this;
    }

    public function getNetzbereichnummer(): ?string
    {
        return $this->Netzbereichnummer;
    }

    public function setNetzbereichnummer(?string $Netzbereichnummer): self
    {
        $this->Netzbereichnummer = $Netzbereichnummer;

        return $this;
    }

    public function getTarifkuerzelSWT(): ?string
    {
        return $this->TarifkuerzelSWT;
    }

    public function setTarifkuerzelSWT(?string $TarifkuerzelSWT): self
    {
        $this->TarifkuerzelSWT = $TarifkuerzelSWT;

        return $this;
    }

    public function getPLZ(): ?string
    {
        return $this->PLZ;
    }

    public function setPLZ(?string $PLZ): self
    {
        $this->PLZ = $PLZ;

        return $this;
    }

    public function getOrt(): ?string
    {
        return $this->Ort;
    }

    public function setOrt(?string $Ort): self
    {
        $this->Ort = $Ort;

        return $this;
    }

    public function getNetznutzungskostenArbeitspreis(): ?string
    {
        return $this->NetznutzungskostenArbeitspreis;
    }

    public function setNetznutzungskostenArbeitspreis(?string $NetznutzungskostenArbeitspreis): self
    {
        $this->NetznutzungskostenArbeitspreis = $NetznutzungskostenArbeitspreis;

        return $this;
    }

    public function getNetznutzungskostenGrundpreis(): ?string
    {
        return $this->NetznutzungskostenGrundpreis;
    }

    public function setNetznutzungskostenGrundpreis(?string $NetznutzungskostenGrundpreis): self
    {
        $this->NetznutzungskostenGrundpreis = $NetznutzungskostenGrundpreis;

        return $this;
    }

    public function getNetznutzungskostenMessung(): ?string
    {
        return $this->NetznutzungskostenMessung;
    }

    public function setNetznutzungskostenMessung(?string $NetznutzungskostenMessung): self
    {
        $this->NetznutzungskostenMessung = $NetznutzungskostenMessung;

        return $this;
    }

    public function getKonzessionsabgabe(): ?string
    {
        return $this->Konzessionsabgabe;
    }

    public function setKonzessionsabgabe(?string $Konzessionsabgabe): self
    {
        $this->Konzessionsabgabe = $Konzessionsabgabe;

        return $this;
    }

    public function getEnergiearbeitspreis(): ?string
    {
        return $this->Energiearbeitspreis;
    }

    public function setEnergiearbeitspreis(?string $Energiearbeitspreis): self
    {
        $this->Energiearbeitspreis = $Energiearbeitspreis;

        return $this;
    }

    public function getEnergiegrundpreis(): ?string
    {
        return $this->Energiegrundpreis;
    }

    public function setEnergiegrundpreis(?string $Energiegrundpreis): self
    {
        $this->Energiegrundpreis = $Energiegrundpreis;

        return $this;
    }

    public function getArbeitspreis(): ?string
    {
        return $this->Arbeitspreis;
    }

    public function setArbeitspreis(?string $Arbeitspreis): self
    {
        $this->Arbeitspreis = $Arbeitspreis;

        return $this;
    }

    public function getArbeitspreisNT(): ?string
    {
        return $this->ArbeitspreisNT;
    }

    public function setArbeitspreisNT(?string $ArbeitspreisNT): self
    {
        $this->ArbeitspreisNT = $ArbeitspreisNT;

        return $this;
    }

    public function getGrundpreis(): ?string
    {
        return $this->Grundpreis;
    }

    public function setGrundpreis(?string $Grundpreis): self
    {
        $this->Grundpreis = $Grundpreis;

        return $this;
    }

    public function getMarktgebiet(): ?string
    {
        return $this->Marktgebiet;
    }

    public function setMarktgebiet(?string $Marktgebiet): self
    {
        $this->Marktgebiet = $Marktgebiet;

        return $this;
    }

    public function getVNBCode(): ?string
    {
        return $this->VNBCode;
    }

    public function setVNBCode(?string $VNBCode): self
    {
        $this->VNBCode = $VNBCode;

        return $this;
    }
}
