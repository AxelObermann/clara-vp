<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/account", name="account")
     */
    public function index()
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register( EntityManagerInterface $em,Request $request, MailerInterface $mailer){
        $form = $this->createForm(UserFormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $zahl="";
            for ($i=0;$i<=4;$i++){
                $random = random_int(1, 10);
                $zahl = $zahl.$random;
            }
            $data = $form->getData();
            $user = new User();
            $profile = new Profile();
            $profile->setUser($user);
            $user->setEmail($data["email"]);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $data['password']));
            $user->setActive(false);
            $user->setDeleted(false);
            $user->setRegistrationCode($zahl);
            $em->persist($user);
            $em->persist($profile);
            $em->flush();
            $email = (new TemplatedEmail())
                ->from('vp@energie-ew.de')
                ->to($data["email"])
                ->subject('Konto bestätigung')
                ->htmlTemplate("email/registration.html.twig")
                ->context(["zahl" => $zahl]);
            $mailer->send($email);
            $this->addFlash('success','Der Benutzer wurde angelegt');

            return $this->redirectToRoute("app_login");
        }
        return $this->render('account/register.html.twig',['registerForm' => $form->createView()]);
    }

    /**
     * @Route ("/forgot" , name="forgot_pass")
     */
    public function forgotpassword(EntityManagerInterface $em,Request $request){
        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            dd($request);
        }

        return $this->render("account/forgot.html.twig",['forgotForm' => $form->createView()]);

    }

    /**
     * @Route ("/usersettings" , name="userSettings")
     */
    public function userSettings(){
        return $this->render('default/dashboard.html.twig',[
            'title' => 'Dashboard'
        ]);
    }
}
