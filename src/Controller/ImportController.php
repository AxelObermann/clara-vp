<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Profile;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DateTime;

class ImportController extends AbstractController
{
    private $connectionParams;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->connectionParams = array(
            'dbname' => 'LarsBentlyVP_PortalClara',
            'user' => 'root',
            'password' => 'A67l99m00@',
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
     * @return Response
     * @throws \Doctrine\DBAL\Exception
     * @Route ("/importUsers", name="importUsers")
     */
    public function syncUserData(EntityManagerInterface $entityManager){
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
    public function syncCalendars(EntityManagerInterface $entityManager,UserRepository $userRepository){
        $count = 0;
        $users = $userRepository->findAll();
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        foreach ($users as $user ){
            $sql = "SELECT * FROM calendars where user=".$user->getOldid();
            $stmt = $conn->query($sql); // Simple, but has several drawbacks
            $stmt->execute();
            $row = $stmt->fetch();
            if ($stmt->rowCount() != 0){
                while ($row = $stmt->fetch()){
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
                    $cal->setStreet($row['Straße']);
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
        /*
        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        $sql = "SELECT * FROM calendars";
        $stmt = $conn->query($sql); // Simple, but has several drawbacks
        $stmt->execute();
        //$users = $stmt->fetchAll();

        while ($row = $stmt->fetch()) {
            $user = $userRepository->findOneBy(array('oldid' => $row['user']));
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
            $cal->setStreet($row['Straße']);
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
            //var_dump($user);
            $count++;
        }
        */
        return $this->render("import/index.html.twig",[
            'title' => 'Calendar Import',
            'countCals' => $count,
        ]);
    }
}
