<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DateTime;

class ImportController extends AbstractController
{
    private $connectionParams;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->connectionParams = array(
            'dbname' => 'LarsBentlyVP_PortalClara',
            'user' => 'root',
            'password' => 'A67l99m00@',
            'host' => '127.0.0.1',
            'driver' => 'pdo_mysql',
        );
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
            $user = new User();
            $user->setEmail($row['username']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,"engage"));
            $user->setRoles([$row['role']]);
            $user->setOldid($row['id']);
            $user->setProvstufe($row['provstufe']);
            $user->setActive(false);
            $user->setActive(false);
            $user->setDeletet(false);
            $user->setCreated(new DateTime());
            $entityManager->persist($user);
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

        $conn = \Doctrine\DBAL\DriverManager::getConnection($this->connectionParams);
        $sql = "SELECT * FROM calendars";
        $stmt = $conn->query($sql); // Simple, but has several drawbacks
        $stmt->execute();
        //$users = $stmt->fetchAll();
        $count = 0;
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
        return $this->render("import/index.html.twig",[
            'title' => 'Calendar Import',
            'countCals' => $count,
        ]);
    }
}
