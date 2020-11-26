<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Calendar;
use App\Entity\Customer;
use App\Entity\DeliveryPlace;
use App\Entity\Notification;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\DeliveryPlaceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use DateTime;

class ImportController extends AbstractController
{
    private $connectionParams;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $oldDBUser;
    private $oldDBPassword;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger, string $oldDBUser,$oldDBPassword)
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
    }

    /**
     * @Route("/import", name="import")
     */
    public function index()
    {
        return $this->render('import/index.html.twig', [
            'controller_name' => 'ImportController',
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route ("/syncUsers", name="sync_users")
     */
    public function syncUsers(Request $request,EntityManagerInterface $entityManager, UserRepository $userRepository){
        $inusers = $userRepository->findImportedUsers()->getArrayResult();
        $test="";
        $i = 0;
        $len = count($inusers);
        foreach ($inusers as $u){
            if ($u['oldid']!=""){
                if ($i == $len - 1) {
                    $test .=$u['oldid'];
                }else{
                    $test .=$u['oldid'].",";
                }
            }
            $i++;
        }
        //dd($test);
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        $sql = "SELECT * FROM users WHERE id NOT IN (".$test.")";
        dd($sql);
        $stmt = $conn->Query($sql); // Simple, but has several drawbacks
        $stmt->execute();
        //$users = $stmt->fetchAll();
        $count = 0;

        while ($row = $stmt->fetch()) {
            $profcheck = "";
            $subsql="SELECT * FROM `profiles` WHERE `user_id`=".$row['id'];
            $prof = $conn->query($subsql);
            $prof->execute();

            $user = new User();
            $profile = new Profile();
            $profile->setUser($user);
            $user->setEmail($row['username']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,"engage"));
            $user->setRoles([$row['role']]);
            $user->setOldid($row['id']);
            $user->setProvstufe($row['provstufe']);
            $user->setParentID($row['parentid']);
            $user->setActive(false);
            $user->setDeleted(false);
            $user->setCreated(new DateTime());

            if ($prof->rowCount() != 0){
                $test = $prof->fetch();

                $this->logger->info("User ID:".$row['id']." - FirstName:".$test['firstname']);
                $profile->setFirstName($test['firstname']);
                $profile->setLastName($test['lastname']);
                $profile->setFirma($test['Firma']);
                $profile->setSteuernummer($test['Steuernummer']);
                $profile->setFinanzamt($test['Finanzamt']);
                $profile->setIban($test['IBAN']);
                $profile->setBic($test['BIC']);
                $profile->setBank($test['Bank']);
                $profile->setTelefon($test['telephone']);
                $profile->setPlz($test['plz']);
                $profile->setOrt($test['ort']);
                $profile->setStrassenr($test['strassenr']
                );
            }
            //dd($prof->rowCount());
            $entityManager->persist($user);
            $entityManager->persist($profile);
            $entityManager->flush();
            $count++;
        }
        return $this->render("import/index.html.twig",[
            'title' => 'User Sync',
            'syncUsers' => $count,
        ]);
    }

    /**
     * @return Response
     * @throws \Doctrine\DBAL\Exception
     * @Route ("/importUsers", name="importUsers")
     */
    public function importUserData(EntityManagerInterface $entityManager){
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        $sql = "SELECT * FROM users";
        $stmt = $conn->query($sql); // Simple, but has several drawbacks
        $stmt->execute();
        //$users = $stmt->fetchAll();
        $count = 0;

        while ($row = $stmt->fetch()) {
            $profcheck = "";
            $subsql="SELECT * FROM `profiles` WHERE `user_id`=".$row['id'];
            $prof = $conn->query($subsql);
            $prof->execute();

            $user = new User();
            $profile = new Profile();
            $profile->setUser($user);
            $user->setEmail($row['username']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,"engage"));
            $user->setRoles([$row['role']]);
            $user->setOldid($row['id']);
            $user->setProvstufe($row['provstufe']);
            $user->setParentID($row['parentid']);
            $user->setActive(false);
            $user->setDeleted(false);
            $user->setCreated(new DateTime());

            if ($prof->rowCount() != 0){
                $test = $prof->fetch();

                $this->logger->info("User ID:".$row['id']." - FirstName:".$test['firstname']);
                $profile->setFirstName($test['firstname']);
                $profile->setLastName($test['lastname']);
                $profile->setFirma($test['Firma']);
                $profile->setSteuernummer($test['Steuernummer']);
                $profile->setFinanzamt($test['Finanzamt']);
                $profile->setIban($test['IBAN']);
                $profile->setBic($test['BIC']);
                $profile->setBank($test['Bank']);
                $profile->setTelefon($test['telephone']);
                $profile->setPlz($test['plz']);
                $profile->setOrt($test['ort']);
                $profile->setStrassenr($test['strassenr']
                );
            }
            //dd($prof->rowCount());
            $entityManager->persist($user);
            $entityManager->persist($profile);
            $entityManager->flush();
            $count++;

        }

        return $this->render("import/index.html.twig",[
            'title' => 'User Import',
            'countUsers' => $count,
        ]);

    }

    /**
     * @param EntityManagerInterface $entityManager
     * @throws \Doctrine\DBAL\Exception
     * @Route ("/importCalendars", name="importCalendars")
     */
    public function importCalendars(EntityManagerInterface $entityManager,UserRepository $userRepository){
        $count = 0;
        $users = $userRepository->findAll();
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        foreach ($users as $user ){
            if ($user->getOldid() != ""){
                $sql = "SELECT * FROM calendars where user=".$user->getOldid();
                $stmt = $conn->query($sql); // Simple, but has several drawbacks
                $stmt->execute();
                //$row = $stmt->fetch();
                if ($stmt->rowCount() != 0){
                    while ($row = $stmt->fetchAssociative()){
                        //dd($row);
                        $cal = new Calendar();
                        $cal->setTitle($row['title']);
                        $cal->setDetails($row['details']);
                        $cal->setStart(new DateTime($row['start']));
                        $cal->setEnd(new DateTime($row['end']));
                        $cal->getAllday($row['all_day']);
                        $cal->setStatus($row['status']);
                        $cal->setActive($row['active']);
                        $cal->setUser($user);
                        $cal->setColor($row['color']);
                        $cal->setCustomerName($row['Kundenname']);
                        $cal->setStreet($row['Strasse']);
                        $cal->setStreetNumber($row['HausNr']);
                        $cal->setPlz($row['PLZ']);
                        $cal->setOrt($row['Ort']);
                        $cal->setAnsprechpartner($row['Ansprechpartner']);
                        $cal->setAnrede($row['anrede']);
                        $cal->setEmail($row['Emailadresse']);
                        $cal->setBermerkungen($row['Bemerkungen']);
                        $cal->setTerminType($row['calTerminTppe']);
                        $entityManager->persist($cal);
                        $entityManager->flush();
                        $count++;
                    }
                }
            }

        }

        return $this->render("import/index.html.twig",[
            'title' => 'Calendar Import',
            'countCals' => $count,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @throws \Doctrine\DBAL\Exception
     * @Route ("/importNotifications" , name="importNotifications")
     * @IsGranted ("ROLE_PORTAL_ADMIN")
     */
    public function syncNotifications(EntityManagerInterface $entityManager,UserRepository $userRepository){
        $count = 0;
        $loggedUser = $this->getUser();
        $users = $userRepository->findAll();
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        foreach ($users as $user ) {
            if ($user->getOldid() != "") {
                $sql = "SELECT * FROM notifikations where user_id=" . $user->getOldid();
                $stmt = $conn->executeQuery($sql); // Simple, but has several drawbacks
                //$stmt->execute();
                //$row = $stmt->fetch();
                if ($stmt->rowCount() != 0) {
                    while ($row = $stmt->fetchAssociative()) {
                        $touser = $userRepository->findOneBy(array('oldid' => $row['user_id']));
                        $noti = new Notification();
                        $noti->setFromUser($loggedUser);
                        $noti->setToUser($touser);
                        $noti->setLink($row['link']);
                        $noti->setDoneUntil(new DateTime($row['notidate']));
                        $noti->setText($row['text']);
                        $noti->setSeen($row['gesehen']);
                        $noti->setCreatedAt(new DateTime($row['created']));
                        $noti->setUpdatedAt(new DateTime($row['modified']));
                        $entityManager->persist($noti);
                        $entityManager->flush();
                        $count++;
                    }
                }
            }
        }
        return $this->render("import/index.html.twig",[
            'title' => 'Notification Import',
            'countNotis' => $count,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @Route ("/importCustomers", name="importCustomers")
     */
    public function importCustomerData(EntityManagerInterface $entityManager,UserRepository $userRepository){

        $count = 0;
        $loggedUser = $this->getUser();
        //$users = $userRepository->findAll();
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        $sql = "SELECT * FROM customers";
        $stmt = $conn->executeQuery($sql);
        if ($stmt->rowCount() != 0) {
            while ($row = $stmt->fetchAssociative()) {
                $user = $userRepository->findOneBy(array('oldid' => $row['user_id']));
                if (!$user){
                    $user = $userRepository->find(7);
                }
                $customer = new Customer();
                $customer->setFullName(utf8_encode($row['fullname']));
                $customer->setContactPerson(utf8_encode($row['contactPerson']));
                $customer->setOldId($row['id']);
                $customer->setUser($user);
                $adress = new Adress();
                $adress->setFax($row['fax']);
                $adress->setMail($row['mail']);
                $adress->setPhone($row['phone']);
                $adress->setStreet(utf8_encode($row['street']));
                $adress->setStreetNumber(utf8_encode($row['street_number']));
                $adress->setZip($row['zip']);
                $adress->setTown(utf8_encode($row['town']));
                $adress->setCustomer($customer);
                $adress->setAdresstype('STAMM');
                $entityManager->persist($customer);
                $entityManager->persist($adress);
                $entityManager->flush();
                $count++;
            }
        }
        return $this->render("import/index.html.twig",[
            'title' => 'Kunden Import',
            'countCustomers' => $count,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param CustomerRepository $customerRepository
     * @Route ("syncCustomers" , name="sync_customers")
     */
    public function syncCustomers(EntityManagerInterface $entityManager,UserRepository $userRepository,CustomerRepository $customerRepository){
        $lastid = $customerRepository->getLastImportedId()->getArrayResult();
        //dd($lastid[0]['1']);
        $count = 0;
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        $sql = "SELECT * FROM customers where id>".$lastid[0]['1'];
        dd($sql);
        $stmt = $conn->executeQuery($sql);
        if ($stmt->rowCount() != 0) {
            while ($row = $stmt->fetchAssociative()) {
                dd($row);
            }
        }
    }

    /**
     * @param CustomerRepository $customerRepository
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @param EntityManagerInterface $entityManager
     * @Route ("importDeliveryPlaces" , name="import_deliveryPlaces")
     */
    public function importCustomerKdls(CustomerRepository $customerRepository, DeliveryPlaceRepository $deliveryPlaceRepository,EntityManagerInterface $entityManager){

        $count = 0;
        $loggedUser = $this->getUser();
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);

        $customers = $customerRepository->findAll();
        foreach ($customers as $customer){
            if ($customer->getOldid() != "") {
                $sql = "SELECT * FROM kdls where customer_id=" . $customer->getOldid();
                $stmt = $conn->executeQuery($sql);
                if ($stmt->rowCount() != 0) {
                    while ($row = $stmt->fetchAssociative()) {
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
                        $count++;
                    }
                }
            }
        }
        return $this->render("import/index.html.twig",[
            'title' => 'Kdl Import',
            'countkdls' => $count,
        ]);
    }
}
