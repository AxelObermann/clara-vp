<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
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
        return $this->render('default/dashboard.html.twig',[
            'title' => 'Dashboard'
        ]);
    }

    /**
     * @Route("/createAdmin")
     */
    public function createAdminUser(EntityManagerInterface $entityManager){
        $user = new User();
        $user->setDeleted(false);
        $user->setActive(false);
        $user->setDisplayName('Axel Obermann');
        $user->setEmail('info@bestofstickers.de');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'A67l99m00@' ));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController1',
        ]);
    }
}
