<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Customer;
use App\Entity\DeliveryPlace;
use App\Repository\AdressRepository;
use App\Repository\CustomerRepository;
use App\Repository\DeliveryPlaceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\String_;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
    public function __construct(Security $security,UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger, string $oldDBUser,$oldDBPassword)
    {
        $this->oldDBUser = $oldDBUser;
        $this->oldDBPassword = $oldDBPassword;
        $this->passwordEncoder = $passwordEncoder;
        $this->connectionParams = array(
            'dbname' => 'LarsBentlyVP_PortalClara',
            'user' => $this->oldDBUser,
            'password' => $this->oldDBPassword,
            'host' => '127.0.0.1',
            'driver' => 'pdo_mysql',
        );
        $this->logger = $logger;
        $this->security = $security;
    }



    /**
     * @Route("/customers", name="customers")
     */
    public function index(CustomerRepository $customerRepository,UserRepository $userRepository,EntityManagerInterface $entityManager)
    {
        $conn = $entityManager->getConnection();
        $user = $this->getUser();
        $users = $userRepository->findAll();
        //dd($stmt->fetchAllAssociative());
        $viewName="";
            if ($this->security->isGranted('ROLE_PORTAL_ADMIN')) {
                $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer INNER JOIN adress WHERE customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
                $viewName = "Portal admin";

            }elseif ($this->security->isGranted('ROLE_ADMIN')){
                $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer INNER JOIN adress WHERE customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
                $viewName = "admin";

            }elseif ($this->security->isGranted('ROLE_PORTAL_USER')){
                $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer  INNER JOIN adress WHERE customer.user_id='.$user->getId().' AND customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
                $viewName = "admin";

            }

        $stmt =$conn->executeQuery($query);
        $stmt->execute();
        return $this->render('customer/index.html.twig', [
            'controller_name' => $viewName,
            'customers' => $stmt->fetchAllAssociative(),
            'users' => $users,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customer/importkdls/{id}")
     */
    public function importKDL(AdressRepository $adressRepository,CustomerRepository $customerRepository,Request $request, EntityManagerInterface $entityManager,DeliveryPlaceRepository $deliveryPlaceRepository,$id){
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        $customer = $customerRepository->findOneBy(array('oldId' => $id));
        $adress = $adressRepository->findOneBy(array('Customer' => $customer,'adresstype' => 'STAMM'));
        $sql = "SELECT * FROM kdls WHERE customer_id=".$id;
        $stmt = $conn->Query($sql); // Simple, but has several drawbacks
        $stmt->execute();
        while ($row = $stmt->fetch()) {

            //dd($row);
                $dp = new DeliveryPlace();
                $dp->setSystemID($row['SystemID']);
                $dp->setTarifnummer(utf8_encode($row['Tarifnummer']));
                $dp->setFirmenname(utf8_encode($row['Firmenname']));
                $dp->setUnternehmensform(utf8_encode($row['Unternehmensform']));
                $dp->setAnrede(utf8_encode($row['Anrede']));
                $dp->setTitel(utf8_encode($row['Titel']));
                $dp->setVorname(utf8_encode($row['Vorname']));
                $dp->setNachname(utf8_encode($row['Nachname']));
                $dp->setStrasse(utf8_encode($row['Strasse']));
                $dp->setHausnummer(utf8_encode($row['Hausnummer']));
                $dp->setPLZ($row['PLZ']);
                $dp->setOrt(utf8_encode($row['Ort']));
                $dp->setOldId($row['id']);
                $dp->setTelefon($row['Telefon']);
                $dp->setEmail($row['Email']);
                $dp->setGeburtstag($row['Geburtstag']);
                $dp->setContractadrIIS($row['contractadrIIS']);
                $dp->setBillingadrIIS($row['billingadrIIS']);
                $dp->setReFirma(utf8_encode($row['ReFirma']));
                $dp->setReAnrede(utf8_encode($row['ReAnrede']));
                $dp->setReTitel(utf8_encode($row['ReTitel']));
                $dp->setReVorname(utf8_encode($row['ReVorname']));
                $dp->setReNachname(utf8_encode($row['ReNachname']));
                $dp->setReStrasse(utf8_encode($row['ReStrasse']));
                $dp->setReHausnummer(utf8_encode($row['ReHausnummer']));
                $dp->setRePLZ(utf8_encode($row['RePLZ']));
                $dp->setReOrt(utf8_encode($row['ReOrt']));
                $dp->setReTelefon(utf8_encode($row['ReTelefon']));
                $dp->setReEmail($row['ReEmail']);
                $dp->setReGeburtstag($row['ReGeburtstag']);
                $dp->setVersorger(utf8_encode($row['Versorger']));
                $dp->setTarifname(utf8_encode($row['Tarifname']));
                $dp->setVorversorger(utf8_encode($row['Vorversorger']));
                $dp->setVorversorgerCode(utf8_encode($row['VorversorgerCode']));
                $dp->setKundennummer(utf8_encode($row['Kundennummer']));
                $dp->setAuftragsdatum($row['Auftragsdatum']);
                $dp->setAuftragsdatum($row['Auftragsdatum']);
                $dp->setVertragsbeginn($row['Vertragsbeginn']);
                $dp->setDauer(utf8_encode($row['Dauer']));
                $dp->setMedium(utf8_encode($row['Medium']));
                $dp->setMediumTyp(utf8_encode($row['MediumTyp']));
                $dp->setKundenart(utf8_encode($row['Kundenart']));
                $dp->setZaehlernummer(utf8_encode($row['Zaehlernummer']));
                $dp->setMaloID(utf8_encode($row['MaloID']));
                $dp->setMeloID(utf8_encode($row['MeloID']));
                $dp->setZaehlertyp(utf8_encode($row['Zaehlertyp']));
                $dp->setSeperaterZaehler(utf8_encode($row['SeperaterZaehler']));
                $dp->setVerbrauch($row['Verbrauch']);
                $dp->setVerbrauchHT($row['VerbrauchHT']);
                $dp->setVerbrauchNT($row['VerbrauchNT']);
                $dp->setAP(utf8_encode($row['AP']));
                $dp->setAPbrutto(utf8_encode($row['APbrutto']));
                $dp->setAPHT(utf8_encode($row['APHT']));
                $dp->setAPHTbrutto(utf8_encode($row['APHTbrutto']));
                $dp->setAPNT(utf8_encode($row['APNT']));
                $dp->setAPNTbrutto(utf8_encode($row['APNTbrutto']));
                $dp->setGP(utf8_encode($row['GP']));
                $dp->setGPBrutto(utf8_encode($row['GP_brutto']));
                $dp->setAbschlussprovision(utf8_encode($row['Abschlussprovision']));
                $dp->setLifetimeprovM(utf8_encode($row['LifetimeprovM']));
                $dp->setFolgeprovM(utf8_encode($row['FolgeprovM']));
                $dp->setFolgeprovisionJ(utf8_encode($row['FolgeprovisionJ']));
                $dp->setBonusProvision(utf8_encode($row['BonusProvision']));
                $dp->setBonusProvisionVerl($row['BonusProvisionVerl']);
                $dp->setStatus(utf8_encode($row['Status']));
                $dp->setBonusCode(utf8_encode($row['BonusCode']));
                $dp->setSpannePKwH(utf8_encode($row['SpannePKwH']));
                $dp->setCustomer($customer);
                $dp->setDeleted(false);
                $dp->setInbelieferung($row['inbelieferung']);
                $dp->setBelieferungsstart($row['belieferungsstart']);
                $dp->setVersKdNr($row['VersKdNr']);
                //dd($dp);
                $entityManager->persist($dp);
                $entityManager->flush();
                 //dd($dp);
             }
        //dd($sql,$customer,$adress);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customer/edit/{id}", methods={"POST"})
     */
    public function getCustomer(SerializerInterface $serializer,CustomerRepository $customerRepository,Request $request,AdressRepository $adressRepository,$id){
        $customer = $customerRepository->getCust($id);
        $adress = $adressRepository->findOneBy(['Customer' => $request->attributes->get('id'),'adresstype' => 'STAMM']);
        //dd($customer,$adress);
        return new JsonResponse([$customer[0]]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customer/getAdress/{id}")
     */
    public function getCustomerAdress(SerializerInterface $serializer,CustomerRepository $customerRepository,Request $request,AdressRepository $adressRepository,$id){
        $customer = $customerRepository->getCust($id);
        $adress = $adressRepository->getCustomerAdress($id,'STAMM');
        //dd($adress[0]);
        return new JsonResponse([$adress[0]]);
    }

    /**
     * @param $id
     * @param CustomerRepository $customerRepository
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customer/getkdls/{id}")
     */
    public function getCustomerKDL($id, CustomerRepository $customerRepository,DeliveryPlaceRepository $deliveryPlaceRepository){
        $customer = $customerRepository->find($id);
        $kdls = $deliveryPlaceRepository->getKdls($id);
        return new JsonResponse($kdls);
    }

    /**
     * @param $id
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customer/get_kdl/{id}")
     */
    public function getSingleDeliveryPlace($id, DeliveryPlaceRepository $deliveryPlaceRepository){
        $kdl = $deliveryPlaceRepository->getSingleKdl($id);
        return new JsonResponse($kdl);
    }
    /**
     * @param $id
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customeredit_kdl/{id}")
     */
    public function getSingleDP($id, DeliveryPlaceRepository $deliveryPlaceRepository){
        $kdl = $deliveryPlaceRepository->getSingleKdl($id);
        //dd($kdl);
        return  $this->render( 'customer/editDP.html.twig',[
            'deliverPlace' => $kdl[0]
        ]);
        //return new JsonResponse($kdl);
    }

    /**
     * @param Request $request
     * @Route ("/customer/saveCustomer/")
     */
    public function saveCustomer(Request $request,CustomerRepository $customerRepository,AdressRepository $adressRepository,EntityManagerInterface $entityManager,UserRepository $userRepository){

        $rp = [];
        $message="";
        if ($content = $request->getContent()) {
            $rp = json_decode($content, true);
        }


        if ($rp['action'] == "new"){
            //dd($rp);
            $user = $userRepository->find($rp['user']);
            $customer = new Customer();
            $customer->setUser($user);
            $customer->setContactPerson($rp['contactPerson']);
            $customer->setFullName($rp['Name']);
            $entityManager->persist($customer);
            $adress = new Adress();
            $adress->setCustomer($customer);
            $adress->setStreet($rp['street']);
            $adress->setStreetNumber($rp['strnumber']);
            $adress->setZip($rp['PLZ']);
            $adress->setTown($rp['Town']);
            $adress->setPhone($rp['phone']);
            $adress->setFax($rp['fax']);
            $adress->setMail($rp['mail']);
            $adress->setAdresstype('STAMM');
            $entityManager->persist($adress);
            $entityManager->flush($customer,$adress);
            $message = 'Der Kunde wurde angelegt!';
        }else{
            $customer = $customerRepository->find($rp['Cid']);
            $adress = $adressRepository->find($rp['Aid']);
            $customer->setContactPerson($rp['contactPerson']);
            $customer->setFullName($rp['Name']);
            $adress->setStreet($rp['street']);
            $adress->setStreetNumber($rp['strnumber']);
            $adress->setZip($rp['PLZ']);
            $adress->setTown($rp['Town']);
            $adress->setPhone($rp['phone']);
            $adress->setFax($rp['fax']);
            $adress->setMail($rp['mail']);
            $entityManager->flush($customer);
            $entityManager->flush($adress);
            $message = 'Die Änderungen wurden übernommen!';
        }

        return new JsonResponse($message);
    }

    /**
     * @param Request $request
     * @Route ("/customer/saveDeliveryPlace/")
     */
    public function saveDeliveryPlace(Request $request,DeliveryPlaceRepository $deliveryPlaceRepository ,EntityManagerInterface $entityManager){
        $rp = [];
        if ($content = $request->getContent()) {
            $rp = json_decode($content, true);
        }
        $dePLace = $deliveryPlaceRepository->find($rp['dpId']);
        $dePLace->setFirmenname($rp['Firmenname']);
        $dePLace->setAnrede($rp['Anrede']);
        $dePLace->setVorname($rp['Vorname']);
        $dePLace->setNachname($rp['Nachname']);
        $dePLace->setStrasse($rp['Strasse']);
        $dePLace->setHausnummer($rp['Hausnummer']);
        $dePLace->setPLZ($rp['PLZ']);
        $dePLace->setOrt($rp['Ort']);
        $dePLace->setReFirma($rp['ReFirma']);
        $dePLace->setReAnrede($rp['ReAnrede']);
        $dePLace->setReVorname($rp['ReVorname']);
        $dePLace->setReNachname($rp['ReNachname']);
        $dePLace->setReStrasse($rp['ReStrasse']);
        $dePLace->setReHausnummer($rp['ReHausnummer']);
        $dePLace->setRePLZ($rp['RePLZ']);
        $dePLace->setReOrt($rp['ReOrt']);
        $dePLace->setIban($rp['IBAN']);
        $dePLace->setBic($rp['BIC']);
        $dePLace->setVorversorger($rp['Vorversorger']);
        $dePLace->setKundennummer($rp['Kundennummer']);
        $dePLace->setKundenart($rp['Kundenart']);
        $dePLace->setVerbrauch($rp['Verbrauch']);
        $dePLace->setMaloID($rp['MaloID']);
        $dePLace->setZaehlernummer($rp['Zaehlernummer']);
        $dePLace->setMeloID($rp['MeloID']);
        $dePLace->setMedium($rp['Medium']);
        $dePLace->setVersorger($rp['Versorger']);
        $dePLace->setTarifname($rp['Tarifname']);
        $dePLace->setTarifnummer($rp['Tarifnummer']);
        $dePLace->setVersKdNr($rp['VersKdNr']);
        $dePLace->setAbschlussprovision($rp['Abschlussprovision']);
        $dePLace->setFolgeprovM($rp['FolgeprovM']);
        $dePLace->setSpannePKwH($rp['SpannePKwH']);
        $dePLace->setAP($rp['AP']);
        $dePLace->setGP($rp['GP']);
        $dePLace->setVertragsbeginn($rp['Vertragsbeginn']);
        $dePLace->setDauer($rp['Dauer']);
        $entityManager->flush($dePLace);
        return new JsonResponse('Die Änderungen wurden übernommen!');
    }
}
