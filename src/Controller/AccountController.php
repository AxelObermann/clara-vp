<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use function Symfony\Component\String\u;

class AccountController extends AbstractController
{

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

        if($request->isMethod('POST')){
            dd($request);
        }
        /*if($form->isSubmitted() && $form->isValid()){
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
        }*/
        return $this->render('account/register.html.twig');
    }

    /**
     * @Route ("/forgot" , name="forgot_pass")
     */
    public function forgotpassword(EntityManagerInterface $em,Request $request){
        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($form->isSubmitted() && $form->isValid()){
            dd($request);
        }


        return $this->render("account/forgot.html.twig",[
            'forgotForm' => $form->createView(),
            'user' => $user]);

    }



    /**
     * @Route ("/usersettings" , name="userSettings")
     */
    public function userSettings(){
        return $this->render('default/dashboard.html.twig',[
            'title' => 'Dashboard'
        ]);
    }

    /**
     * @Route("/userprofile", name="userprofile")
     * @IsGranted("ROLE_USER")
     */
    public function userProfile (Request $request, EntityManagerInterface $entityManager){
        if ($request->isMethod('POST')){
            $profile = $this->getUser()->getProfile();
            $profile->setFirstName($request->request->get('Vorname'));
            $profile->setLastName($request->request->get('Nachname'));
            $profile->setFirma($request->request->get('Firma'));
            $profile->setSteuernummer($request->request->get('Steuernummer'));
            $profile->setFinanzamt($request->request->get('Finanzamt'));
            $profile->setIban($request->request->get('IBAN'));
            $profile->setBic($request->request->get('BIC'));
            $profile->setBank($request->request->get('Bank'));
            $profile->setTelefon($request->request->get('Telefon'));
            $profile->setPlz($request->request->get('PLZ'));
            $profile->setOrt($request->request->get('Ort'));
            $profile->setStrassenr($request->request->get('Strasse'));
            $profile->getSignatur($request->request->get('Signatur'));
            $entityManager->flush();

        }
        $user = $this->getUser();
        return $this->render("account/profile.html.twig", [
            'profile' => $user->getProfile()]);
    }

    /**
     * @Route("/userupdate", name="userupdate")
     * @IsGranted("ROLE_USER")
     */
    public function userupdate (Request $request, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper){
        $user = $this->getUser();

        if ($request->isMethod('POST')){
            //dd($request);
            $uploadedFile = $request->files->get("userImage");
            $uploadedLogo = $request->files->get("userLogo");

            if ($uploadedFile){
                $profileImage = $uploaderHelper->uploadProfileImage($uploadedFile,$user->getId());
                $profile = $this->getUser()->getProfile();
                $profile->setImage($profileImage);
                $entityManager->flush();
            }
            if ($uploadedLogo){
                $profileImage = $uploaderHelper->uploadProfileImage($uploadedLogo,$user->getId());
                $profile = $this->getUser()->getProfile();
                $profile->setLogo($profileImage);
                $entityManager->flush();
            }
            if($request->request->get('oldpass') != ""){
                if (!$this->passwordEncoder->isPasswordValid($user,$request->request->get('oldpass'))){
                    $this->addFlash('error', 'Das eingegebene Passwort stimmt nicht!');
                    return $this->render("account/profile.html.twig", [
                        'profile' => $user->getProfile()]);
                }else{
                    $pass1 = $request->request->get('newpass');
                    $pass2 = $request->request->get('confirmnewpass');
                    if ($pass1 != $pass2){
                        $this->addFlash('error', 'Die Passwörter stimmen nicht überein!!');
                        return $this->render("account/profile.html.twig", [
                            'profile' => $user->getProfile()]);
                    }else{
                        $user->setPassword($this->passwordEncoder->encodePassword($user, $request->request->get('newpass')));
                    }

                }
            }

            $user->setDisplayName($request->request->get('displayname'));
            $user->setEmail($request->request->get('email'));
            $entityManager->flush();
            $this->session->set('userImage', $this->getUser()->getProfile()->getImage());
            $this->addFlash('success', 'die Änderungen wurden übernommen');
            return $this->render("account/profile.html.twig", [
                'profile' => $user->getProfile()]);
        }


        return $this->render("account/profile.html.twig", [
            'profile' => $user->getProfile()]);
    }

    /**
     * @Route ("/tempUpdload", name="temp_upload")
     */
    public function tempUpload(EntityManagerInterface $entityManager, Request $request, UploaderHelper $uploaderHelper){
        /** @var UploadedFile $uploadedFile */

        $uploadedFile = $request->files->get("profilePicture");
        $user = $this->getUser();
        if ($uploadedFile){
            $profileImage = $uploaderHelper->uploadProfileImage($uploadedFile,$user->getId());
            $profile = $this->getUser()->getProfile();
            $profile->setImage($profileImage);
            $entityManager->flush();
        }


        return $this->render("account/profile.html.twig", [
            'profile' => $user->getProfile()]);



        $destination = $this->getParameter('kernel.project_dir').'/public/UPLOADS';
        $newFileName = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
        $uploadedFile->move($destination,$newFileName."-".uniqid().".".$uploadedFile->guessExtension());
    }
}
