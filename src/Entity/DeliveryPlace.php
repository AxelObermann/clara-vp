<?php

namespace App\Entity;

use App\Repository\DeliveryPlaceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeliveryPlaceRepository::class)
 */
class DeliveryPlace
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $SystemID;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Tarifnummer;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Firmenname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Unternehmensform;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Anrede;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Titel;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Vorname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Nachname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Strasse;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Hausnummer;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $PLZ;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Ort;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $Telefon;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $Geburtstag;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $contractadrIIS;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $billingadrIIS;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReFirma;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReAnrede;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReTitel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReVorname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReNachname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReStrasse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReHausnummer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $RePLZ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReOrt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReTelefon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReEmail;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ReGeburtstag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Versorger;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Tarifname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Vorversorger;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $VorversorgerCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Kundennummer;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $Auftragsdatum;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $Vertragsbeginn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Dauer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Medium;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $MediumTyp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Kundenart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Zaehlernummer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $MaloID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $MeloID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Zaehlertyp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SeperaterZaehler;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Verbrauch;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $VerbrauchHT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $VerbrauchNT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $AP;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $APbrutto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $APHT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $APHTbrutto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $APNT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $APNTbrutto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $GP;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $GP_brutto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Abschlussprovision;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $LifetimeprovM;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $FolgeprovisionJ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $FolgeprovM;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $BonusProvision;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $BonusProvisionVerl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $BonusCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SpannePKwH;

    /**
     * @ORM\Column(type="integer")
     */
    private $oldId;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="deliveryPlaces")
     */
    private $customer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inbelieferung;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $belieferungsstart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $VersKdNr;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Iban;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Bic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSystemID(): ?string
    {
        return $this->SystemID;
    }

    public function setSystemID(?string $SystemID): self
    {
        $this->SystemID = $SystemID;

        return $this;
    }

    public function getTarifnummer(): ?string
    {
        return $this->Tarifnummer;
    }

    public function setTarifnummer(?string $Tarifnummer): self
    {
        $this->Tarifnummer = $Tarifnummer;

        return $this;
    }

    public function getFirmenname(): ?string
    {
        return $this->Firmenname;
    }

    public function setFirmenname(?string $Firmenname): self
    {
        $this->Firmenname = $Firmenname;

        return $this;
    }

    public function getUnternehmensform(): ?string
    {
        return $this->Unternehmensform;
    }

    public function setUnternehmensform(?string $Unternehmensform): self
    {
        $this->Unternehmensform = $Unternehmensform;

        return $this;
    }

    public function getAnrede(): ?string
    {
        return $this->Anrede;
    }

    public function setAnrede(?string $Anrede): self
    {
        $this->Anrede = $Anrede;

        return $this;
    }

    public function getTitel(): ?string
    {
        return $this->Titel;
    }

    public function setTitel(?string $Titel): self
    {
        $this->Titel = $Titel;

        return $this;
    }

    public function getVorname(): ?string
    {
        return $this->Vorname;
    }

    public function setVorname(?string $Vorname): self
    {
        $this->Vorname = $Vorname;

        return $this;
    }

    public function getNachname(): ?string
    {
        return $this->Nachname;
    }

    public function setNachname(?string $Nachname): self
    {
        $this->Nachname = $Nachname;

        return $this;
    }

    public function getStrasse(): ?string
    {
        return $this->Strasse;
    }

    public function setStrasse(?string $Strasse): self
    {
        $this->Strasse = $Strasse;

        return $this;
    }

    public function getHausnummer(): ?string
    {
        return $this->Hausnummer;
    }

    public function setHausnummer(?string $Hausnummer): self
    {
        $this->Hausnummer = $Hausnummer;

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

    public function getTelefon(): ?string
    {
        return $this->Telefon;
    }

    public function setTelefon(?string $Telefon): self
    {
        $this->Telefon = $Telefon;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(?string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getGeburtstag(): ?string
    {
        return $this->Geburtstag;
    }

    public function setGeburtstag(?string $Geburtstag): self
    {
        $this->Geburtstag = $Geburtstag;

        return $this;
    }

    public function getContractadrIIS(): ?string
    {
        return $this->contractadrIIS;
    }

    public function setContractadrIIS(?string $contractadrIIS): self
    {
        $this->contractadrIIS = $contractadrIIS;

        return $this;
    }

    public function getBillingadrIIS(): ?string
    {
        return $this->billingadrIIS;
    }

    public function setBillingadrIIS(?string $billingadrIIS): self
    {
        $this->billingadrIIS = $billingadrIIS;

        return $this;
    }

    public function getReFirma(): ?string
    {
        return $this->ReFirma;
    }

    public function setReFirma(?string $ReFirma): self
    {
        $this->ReFirma = $ReFirma;

        return $this;
    }

    public function getReAnrede(): ?string
    {
        return $this->ReAnrede;
    }

    public function setReAnrede(?string $ReAnrede): self
    {
        $this->ReAnrede = $ReAnrede;

        return $this;
    }

    public function getReTitel(): ?string
    {
        return $this->ReTitel;
    }

    public function setReTitel(?string $ReTitel): self
    {
        $this->ReTitel = $ReTitel;

        return $this;
    }

    public function getReVorname(): ?string
    {
        return $this->ReVorname;
    }

    public function setReVorname(?string $ReVorname): self
    {
        $this->ReVorname = $ReVorname;

        return $this;
    }

    public function getReNachname(): ?string
    {
        return $this->ReNachname;
    }

    public function setReNachname(?string $ReNachname): self
    {
        $this->ReNachname = $ReNachname;

        return $this;
    }

    public function getReStrasse(): ?string
    {
        return $this->ReStrasse;
    }

    public function setReStrasse(?string $ReStrasse): self
    {
        $this->ReStrasse = $ReStrasse;

        return $this;
    }

    public function getReHausnummer(): ?string
    {
        return $this->ReHausnummer;
    }

    public function setReHausnummer(?string $ReHausnummer): self
    {
        $this->ReHausnummer = $ReHausnummer;

        return $this;
    }

    public function getRePLZ(): ?string
    {
        return $this->RePLZ;
    }

    public function setRePLZ(?string $RePLZ): self
    {
        $this->RePLZ = $RePLZ;

        return $this;
    }

    public function getReOrt(): ?string
    {
        return $this->ReOrt;
    }

    public function setReOrt(?string $ReOrt): self
    {
        $this->ReOrt = $ReOrt;

        return $this;
    }

    public function getReTelefon(): ?string
    {
        return $this->ReTelefon;
    }

    public function setReTelefon(?string $ReTelefon): self
    {
        $this->ReTelefon = $ReTelefon;

        return $this;
    }

    public function getReEmail(): ?string
    {
        return $this->ReEmail;
    }

    public function setReEmail(?string $ReEmail): self
    {
        $this->ReEmail = $ReEmail;

        return $this;
    }

    public function getReGeburtstag(): ?string
    {
        return $this->ReGeburtstag;
    }

    public function setReGeburtstag(?string $ReGeburtstag): self
    {
        $this->ReGeburtstag = $ReGeburtstag;

        return $this;
    }

    public function getVersorger(): ?string
    {
        return $this->Versorger;
    }

    public function setVersorger(?string $Versorger): self
    {
        $this->Versorger = $Versorger;

        return $this;
    }

    public function getTarifname(): ?string
    {
        return $this->Tarifname;
    }

    public function setTarifname(?string $Tarifname): self
    {
        $this->Tarifname = $Tarifname;

        return $this;
    }

    public function getVorversorger(): ?string
    {
        return $this->Vorversorger;
    }

    public function setVorversorger(?string $Vorversorger): self
    {
        $this->Vorversorger = $Vorversorger;

        return $this;
    }

    public function getVorversorgerCode(): ?string
    {
        return $this->VorversorgerCode;
    }

    public function setVorversorgerCode(?string $VorversorgerCode): self
    {
        $this->VorversorgerCode = $VorversorgerCode;

        return $this;
    }

    public function getKundennummer(): ?string
    {
        return $this->Kundennummer;
    }

    public function setKundennummer(?string $Kundennummer): self
    {
        $this->Kundennummer = $Kundennummer;

        return $this;
    }

    public function getAuftragsdatum(): ?string
    {
        return $this->Auftragsdatum;
    }

    public function setAuftragsdatum(?string $Auftragsdatum): self
    {
        $this->Auftragsdatum = $Auftragsdatum;

        return $this;
    }

    public function getVertragsbeginn(): ?string
    {
        return $this->Vertragsbeginn;
    }

    public function setVertragsbeginn(?string $Vertragsbeginn): self
    {
        $this->Vertragsbeginn = $Vertragsbeginn;

        return $this;
    }

    public function getDauer(): ?string
    {
        return $this->Dauer;
    }

    public function setDauer(?string $Dauer): self
    {
        $this->Dauer = $Dauer;

        return $this;
    }

    public function getMedium(): ?string
    {
        return $this->Medium;
    }

    public function setMedium(?string $Medium): self
    {
        $this->Medium = $Medium;

        return $this;
    }

    public function getMediumTyp(): ?string
    {
        return $this->MediumTyp;
    }

    public function setMediumTyp(?string $MediumTyp): self
    {
        $this->MediumTyp = $MediumTyp;

        return $this;
    }

    public function getKundenart(): ?string
    {
        return $this->Kundenart;
    }

    public function setKundenart(?string $Kundenart): self
    {
        $this->Kundenart = $Kundenart;

        return $this;
    }

    public function getZaehlernummer(): ?string
    {
        return $this->Zaehlernummer;
    }

    public function setZaehlernummer(?string $Zaehlernummer): self
    {
        $this->Zaehlernummer = $Zaehlernummer;

        return $this;
    }

    public function getMaloID(): ?string
    {
        return $this->MaloID;
    }

    public function setMaloID(?string $MaloID): self
    {
        $this->MaloID = $MaloID;

        return $this;
    }

    public function getMeloID(): ?string
    {
        return $this->MeloID;
    }

    public function setMeloID(?string $MeloID): self
    {
        $this->MeloID = $MeloID;

        return $this;
    }

    public function getZaehlertyp(): ?string
    {
        return $this->Zaehlertyp;
    }

    public function setZaehlertyp(?string $Zaehlertyp): self
    {
        $this->Zaehlertyp = $Zaehlertyp;

        return $this;
    }

    public function getSeperaterZaehler(): ?string
    {
        return $this->SeperaterZaehler;
    }

    public function setSeperaterZaehler(?string $SeperaterZaehler): self
    {
        $this->SeperaterZaehler = $SeperaterZaehler;

        return $this;
    }

    public function getVerbrauch(): ?string
    {
        return $this->Verbrauch;
    }

    public function setVerbrauch(?string $Verbrauch): self
    {
        $this->Verbrauch = $Verbrauch;

        return $this;
    }

    public function getVerbrauchHT(): ?string
    {
        return $this->VerbrauchHT;
    }

    public function setVerbrauchHT(?string $VerbrauchHT): self
    {
        $this->VerbrauchHT = $VerbrauchHT;

        return $this;
    }

    public function getVerbrauchNT(): ?string
    {
        return $this->VerbrauchNT;
    }

    public function setVerbrauchNT(?string $VerbrauchNT): self
    {
        $this->VerbrauchNT = $VerbrauchNT;

        return $this;
    }

    public function getAP(): ?string
    {
        return $this->AP;
    }

    public function setAP(?string $AP): self
    {
        $this->AP = $AP;

        return $this;
    }

    public function getAPbrutto(): ?string
    {
        return $this->APbrutto;
    }

    public function setAPbrutto(?string $APbrutto): self
    {
        $this->APbrutto = $APbrutto;

        return $this;
    }

    public function getAPHT(): ?string
    {
        return $this->APHT;
    }

    public function setAPHT(?string $APHT): self
    {
        $this->APHT = $APHT;

        return $this;
    }

    public function getAPHTbrutto(): ?string
    {
        return $this->APHTbrutto;
    }

    public function setAPHTbrutto(?string $APHTbrutto): self
    {
        $this->APHTbrutto = $APHTbrutto;

        return $this;
    }

    public function getAPNT(): ?string
    {
        return $this->APNT;
    }

    public function setAPNT(?string $APNT): self
    {
        $this->APNT = $APNT;

        return $this;
    }

    public function getAPNTbrutto(): ?string
    {
        return $this->APNTbrutto;
    }

    public function setAPNTbrutto(?string $APNTbrutto): self
    {
        $this->APNTbrutto = $APNTbrutto;

        return $this;
    }

    public function getGP(): ?string
    {
        return $this->GP;
    }

    public function setGP(?string $GP): self
    {
        $this->GP = $GP;

        return $this;
    }

    public function getGPBrutto(): ?string
    {
        return $this->GP_brutto;
    }

    public function setGPBrutto(?string $GP_brutto): self
    {
        $this->GP_brutto = $GP_brutto;

        return $this;
    }

    public function getAbschlussprovision(): ?string
    {
        return $this->Abschlussprovision;
    }

    public function setAbschlussprovision(?string $Abschlussprovision): self
    {
        $this->Abschlussprovision = $Abschlussprovision;

        return $this;
    }

    public function getLifetimeprovM(): ?string
    {
        return $this->LifetimeprovM;
    }

    public function setLifetimeprovM(?string $LifetimeprovM): self
    {
        $this->LifetimeprovM = $LifetimeprovM;

        return $this;
    }

    public function getFolgeprovisionJ(): ?string
    {
        return $this->FolgeprovisionJ;
    }

    public function setFolgeprovisionJ(?string $FolgeprovisionJ): self
    {
        $this->FolgeprovisionJ = $FolgeprovisionJ;

        return $this;
    }

    public function getFolgeprovM(): ?string
    {
        return $this->FolgeprovM;
    }

    public function setFolgeprovM(?string $FolgeprovM): self
    {
        $this->FolgeprovM = $FolgeprovM;

        return $this;
    }

    public function getBonusProvision(): ?string
    {
        return $this->BonusProvision;
    }

    public function setBonusProvision(?string $BonusProvision): self
    {
        $this->BonusProvision = $BonusProvision;

        return $this;
    }

    public function getBonusProvisionVerl(): ?string
    {
        return $this->BonusProvisionVerl;
    }

    public function setBonusProvisionVerl(?string $BonusProvisionVerl): self
    {
        $this->BonusProvisionVerl = $BonusProvisionVerl;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(?string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getBonusCode(): ?string
    {
        return $this->BonusCode;
    }

    public function setBonusCode(?string $BonusCode): self
    {
        $this->BonusCode = $BonusCode;

        return $this;
    }

    public function getSpannePKwH(): ?string
    {
        return $this->SpannePKwH;
    }

    public function setSpannePKwH(?string $SpannePKwH): self
    {
        $this->SpannePKwH = $SpannePKwH;

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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

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

    public function getInbelieferung(): ?bool
    {
        return $this->inbelieferung;
    }

    public function setInbelieferung(bool $inbelieferung): self
    {
        $this->inbelieferung = $inbelieferung;

        return $this;
    }

    public function getBelieferungsstart(): ?string
    {
        return $this->belieferungsstart;
    }

    public function setBelieferungsstart(?string $belieferungsstart): self
    {
        $this->belieferungsstart = $belieferungsstart;

        return $this;
    }

    public function getVersKdNr(): ?string
    {
        return $this->VersKdNr;
    }

    public function setVersKdNr(?string $VersKdNr): self
    {
        $this->VersKdNr = $VersKdNr;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->Iban;
    }

    public function setIban(?string $Iban): self
    {
        $this->Iban = $Iban;

        return $this;
    }

    public function getBic(): ?string
    {
        return $this->Bic;
    }

    public function setBic(?string $Bic): self
    {
        $this->Bic = $Bic;

        return $this;
    }
}
