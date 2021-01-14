<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Customer;
use App\Entity\DeliveryPlace;
use App\Entity\Notification;
use App\Form\CustomerFormType;
use App\Repository\AdressRepository;
use App\Repository\CustomerRepository;
use App\Repository\DeliverPlaceCheckRepository;
use App\Repository\DeliveryPlaceRepository;
use App\Repository\NotificationRepository;
use App\Repository\SupplierRepository;
use App\Repository\UserRepository;
use App\Service\MessagesService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\String_;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\Date;
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
    private $session;
    public function __construct(Security $security,UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger, string $oldDBUser,$oldDBPassword,SessionInterface $session)
    {
        $this->session = $session;
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
     * @Route("/kundenindex", name="customers")
     */
    public function index(Request $request,CustomerRepository $customerRepository,UserRepository $userRepository,EntityManagerInterface $entityManager, SupplierRepository $supplierRepository)
    {
        $this->session->set('lastUrl', $request->getPathInfo());
        $conn = $entityManager->getConnection();
        $user = $userRepository->find($this->getUser()->getId());
        $users = $userRepository->findAll();
        $suppliers = $supplierRepository->findAll();
        //dd($stmt->fetchAllAssociative());
        $viewName="";
            if ($this->security->isGranted('ROLE_PORTAL_ADMIN')) {
                $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer INNER JOIN adress WHERE deleted=0 AND customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
                $viewName = "Portal admin";

            }elseif ($this->security->isGranted('ROLE_ADMIN')){
                $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer INNER JOIN adress WHERE deleted=0 AND customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
                $viewName = "admin";

            }elseif ($this->security->isGranted('ROLE_PORTAL_USER')){
                $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer  INNER JOIN adress WHERE deleted=0 AND customer.user_id='.$user->getId().' AND customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
                $viewName = "admin";

            }elseif ($this->security->isGranted('ROLE_FACILITY_MANAGER')){
                $test = null;
                //dd($user->getAllowedCustomer());
                if ($user->getAllowedCustomer() != null){
                    foreach ($user->getAllowedCustomer() as $cust){
                        //dd($cust);
                        $test .= $cust.",";
                    }
                }
                if ($test==null){
                    $query = "";
                    $customers="";
                }else{
                    $test = substr($test,0,strlen($test)-1);
                    //dd($test);
                    $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer  INNER JOIN adress WHERE deleted=0 AND customer.id in ('.$test.')  AND customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
                    $viewName = "admin";
                }
            }
        if ($query!="") {
            $stmt = $conn->executeQuery($query);
            $stmt->execute();
            $customers = $stmt->fetchAllAssociative();
        }
        //dd($stmt->fetchAllAssociative());
        return $this->render('customer/indexNoJs.html.twig', [
            'controller_name' => $viewName,
            'customers' => $customers,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/kundenindex/edit/{id}")
     */
    public function edit(Request $request,EntityManagerInterface $entityManager,CustomerRepository $customerRepository,UserRepository $userRepository,AdressRepository $adressRepository,DeliveryPlaceRepository $deliveryPlaceRepository){
        $this->session->set('lastUrl', $request->getPathInfo());
        $customer = $customerRepository->find($request->get('id'));
        if($request->isMethod('POST')){
            $adress = $adressRepository->find($request->get('aid'));
            $customer->setContactPerson($request->get('contactPerson'));
            $customer->setFullName($request->get('fullName'));
            $adress->setStreet($request->get('street'));
            $adress->setStreetNumber($request->get('strnumber'));
            $adress->setZip($request->get('PLZ'));
            $adress->setTown($request->get('Town'));
            $adress->setPhone($request->get('phone'));
            $adress->setFax($request->get('fax'));
            $adress->setMail($request->get('mail'));
            $entityManager->flush();
            //dd($request,$customer,$customer->getAdress());
        }

        $dpls = $deliveryPlaceRepository->findBy(array('customer' => $customer,'deleted' => 0));

        $conn = $entityManager->getConnection();
        $query = 'SELECT  customer.id AS cid, customer.user_id, customer.full_name,customer.contact_person, adress.id as adrid,adress.street,adress.street_number,adress.zip,adress.town,adress.phone,adress.fax,adress.mail FROM customer INNER JOIN adress WHERE customer.id='.$request->get('id').' AND customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
        $stmt =$conn->executeQuery($query);
        $stmt->execute();
        $users = $userRepository->findAll();
        return $this->render('customer/edit.html.twig',[
            'users' => $users,
            'customer' => $stmt->fetchAssociative(),
            'places' => $dpls,
        ]);
    }



    /**
     * @Route("/customers/show_deleted", name="customers_show_deleted")
     */
    public function showDeleted(CustomerRepository $customerRepository,UserRepository $userRepository,EntityManagerInterface $entityManager)
    {
        $conn = $entityManager->getConnection();
        $user = $this->getUser();
        $users = $userRepository->findAll();
        //dd($stmt->fetchAllAssociative());
        $viewName="";
        if ($this->security->isGranted('ROLE_PORTAL_ADMIN')) {
            $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer INNER JOIN adress WHERE deleted=1 AND customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
            $viewName = "Portal admin";

        }elseif ($this->security->isGranted('ROLE_ADMIN')){
            $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer INNER JOIN adress WHERE deleted=1 AND customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
            $viewName = "admin";

        }elseif ($this->security->isGranted('ROLE_PORTAL_USER')){
            $query = 'SELECT *, (SELECT count(*) FROM delivery_place WHERE delivery_place.customer_id=customer.id) as kdl FROM customer  INNER JOIN adress WHERE deleted=1 AND customer.user_id='.$user->getId().' AND customer.id = adress.customer_id AND adress.adresstype = "STAMM"';
            $viewName = "admin";

        }

        $stmt =$conn->executeQuery($query);
        $stmt->execute();
        return $this->render('customer/indexNoJs.html.twig', [
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
     * @param CustomerRepository $customerRepository
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customer/delete/{id}")
     */
    public function getDelete($id, CustomerRepository $customerRepository,DeliveryPlaceRepository $deliveryPlaceRepository,EntityManagerInterface $entityManager){
        $customer = $customerRepository->find($id);
        $customer->setDeleted(true);
        $entityManager->flush();
        //$kdls = $deliveryPlaceRepository->getKdls($id);
        return new JsonResponse('Der Kunde wurde gelöscht.');
    }

    /**
     * @param $id
     * @param CustomerRepository $customerRepository
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customer/deleteDP/{id}")
     */
    public function deleteDeliveryPlace($id, CustomerRepository $customerRepository,DeliveryPlaceRepository $deliveryPlaceRepository,EntityManagerInterface $entityManager){
        $dp = $deliveryPlaceRepository->find($id);
        $dp->setDeleted(true);
        $entityManager->flush();
        //$kdls = $deliveryPlaceRepository->getKdls($id);
        return new JsonResponse('Die Lieferstelle wurde gelöscht wurde gelöscht.');
    }

    /**
     * @param $id
     * @param CustomerRepository $customerRepository
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customer/deleteDPCheck/{id}")
     */
    public function deleteDeliveryPlaceCheck($id, CustomerRepository $customerRepository,DeliverPlaceCheckRepository $deliverPlaceCheckRepository,EntityManagerInterface $entityManager){
        $dp = $deliverPlaceCheckRepository->find($id);
        $dp->setDeleted(true);
        $entityManager->flush();
        //$kdls = $deliveryPlaceRepository->getKdls($id);
        return new JsonResponse('Die Ablesung wurde gelöscht wurde gelöscht.');
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
     * @param $id
     * @param UserRepository $userRepository
     * @Route ("/customer/getfm/{id}")
     */
    public function getFacilityManager($id,Request $request,UserRepository $userRepository){
        //dd($request->get('id'));

        $fmuser = $userRepository->findFacilityManager($request->get('id'));
        //dd($fmuser);
        if ($fmuser){
            return new JsonResponse($fmuser);
        }else{
            return new JsonResponse(false);
        }
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
            $customer->setDeleted(false);
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
    public function saveDeliveryPlace(UserRepository $userRepository,CustomerRepository $customerRepository,Request $request,DeliveryPlaceRepository $deliveryPlaceRepository ,EntityManagerInterface $entityManager){
        $rp = [];
        $dePLace = null;
        $message = "";
        if ($content = $request->getContent()) {
            $rp = json_decode($content, true);
        }


        if($rp['actionDP'] == 'new'){
            $dePLace = new DeliveryPlace();
            $dePLace->setCustomer($customerRepository->find($rp['DPCustomerID']));
            $dePLace->setDeleted(false);
            $dePLace->setInbelieferung(false);
            $dePLace->setFirmenname($rp['Firmenname']);
            $dePLace->setAnrede($rp['Anrede']);
            $dePLace->setGeburtstag($rp['Geburtstag']);
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
            $dePLace->setStab(new \DateTime($rp['stab']));
            if (isset($rp['facilityUserId'])){
                $facilityUser = $userRepository->find($rp['facilityUserId']);
                $dePLace->setFacilityUser($facilityUser);
                dd($rp);
            }
            $entityManager->persist($dePLace);
            $entityManager->flush();
            $message = 'Die Lieferstelle wurde angelegt';
        }else{
            $dePLace = $deliveryPlaceRepository->find($rp['dpId']);
            $dePLace->setFirmenname($rp['Firmenname']);
            $dePLace->setAnrede($rp['Anrede']);
            $dePLace->setGeburtstag($rp['Geburtstag']);
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
            $dePLace->setStab(new \DateTime($rp['stab']));
            if (isset($rp['facilityUserId'])){
                $facilityUser = $userRepository->find($rp['facilityUserId']);
                $dePLace->setFacilityUser($facilityUser);
                //dd($rp['facilityUserId']);
            }
            $entityManager->persist($dePLace);
            $entityManager->flush();
            $message = 'Die Änderungen wurden übernommen!';
        }

        //dd($dePLace);



        return new JsonResponse($message);
    }

    /**
     * @param Request $request
     * @param NotificationRepository $notificationRepository
     * @param UserRepository $userRepository
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("/customer/sendTodo")
     */
    public function sendManualTodo(MessagesService $messagesService,CustomerRepository $customerRepository,Request $request,NotificationRepository $notificationRepository,UserRepository $userRepository,DeliveryPlaceRepository $deliveryPlaceRepository,EntityManagerInterface $entityManager){
        $rp = [];
        if ($content = $request->getContent()) {
            $rp = json_decode($content, true);
        }
        //dd($rp);

        $adusers = $userRepository->findAdminUsers();
        $fromUser = $userRepository->find($this->getUser()->getId());
        $toUser = $userRepository->find($rp['facilityUserId']);
        $customer = $customerRepository->find($rp['DPCustomerID']);
        $deliveryPlace = $deliveryPlaceRepository->find($rp['dpId']);
        $notification = new Notification();
        $notification->setCreatedAt(new \DateTime());
        $notification->setUpdatedAt(new \DateTime());
        $notification->setCustomer($customer);
        $notification->setDelveryPlace($deliveryPlace);
        $notification->setFromUser($fromUser);
        $notification->setToUser($toUser);
        $notification->setPlz($rp['PLZ']);
        $notification->setZaehlernummer($rp['Zaehlernummer']);
        $notification->setDone(0);
        $notification->setType('4');
        $notification->setText("Neuer Ablesetermin");
        $notification->setDescription($rp['Firmenname']."<br".$rp['Strasse']." ".$rp['Hausnummer']."<br>".$rp['PLZ']." ".$rp['Ort']);
        $notification->setLink("");
        $notification->setDoneUntil(new \DateTime($rp['doneUntil']));
        $entityManager->persist($notification);
        $entityManager->flush();
        $test = $messagesService->sendAdminTodoControlMessage($notification);
        return new JsonResponse('Das Todo wurde angelegt!');
    }

    /**
     * @param Request $request
     * @Route ("/test")
     */
    public function test(NotificationRepository $notificationRepository,MessagesService $messagesService,Request $request, UserRepository $userRepository){
        $noti = $notificationRepository->find(155);
        $test = $messagesService->sendAdminTodoControlMessage($noti);
    }
}
