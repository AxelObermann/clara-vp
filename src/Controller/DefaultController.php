<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,SessionInterface $session)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
    }

    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController1',
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        $this->session->set('userImage', $this->getUser()->getProfile()->getImage());
        return $this->render('default/dashboard.html.twig',[
            'title' => 'Dashboard'
        ]);
    }

    /**
     * @Route("/createAdmin")
     */
    public function createAdminUser(EntityManagerInterface $entityManager){
        $user = new User();
        $profile = new Profile();
        $user->setDeleted(false);
        $user->setActive(false);
        $user->setDisplayName('Axel Obermann');
        $user->setEmail('info@bestofstickers.de');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'A67l99m00@' ));
        $user->setRoles(["ROLE_ADMIN", "ROLE_PORTAL_ADMIN"]);
        $profile->setUser($user);
        $user->setProfile($profile);
        $entityManager->persist($user);
        $entityManager->persist($profile);
        $entityManager->flush();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController1',
        ]);
    }
}
