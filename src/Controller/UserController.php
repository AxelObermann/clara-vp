<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use App\Service\UploaderHelper;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    public function __construct(string $uploadsPath,string  $uploadsDBPath){

        $this->uploadsPath = $uploadsPath;
        $this->uploadsDBPath = $uploadsDBPath;
    }
    /**
     * @Route("/benutzerindex", name="benutzerindex")
     */
    public function index(UserService $userService)
    {
        $users = $userService->getAllUsers();
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route ("/usertogglefavorite", name="user_toggle_favorite")
     */
    public function toggleFavorite(UserService $userService,Request $request,EntityManagerInterface $entityManager){
        if ($request->isMethod('GET')){
            $usertoadd = $request->query->get('id');
            $favs = array();
            $user = $this->getUser();
            $favs = $user->getFavorite();
            //dd($favs);

            if (in_array($usertoadd,$favs)){
                $index = array_search($usertoadd, $favs);
                //dd($index);
                unset($favs[$index]);
                $user->setFavorite($favs);
            }else{
                array_push($favs,$usertoadd);
                $user->setFavorite($favs);
            }
            $entityManager->flush();

        }

        $users = $userService->getAllUsers();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param UserService $userService
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/usertoggleactive", name="user_toggle_active")
     */
    public function toggleActive(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager){
        if ($request->isMethod('GET')){
            $userToActivate = $request->query->get('id');
            $user = $userRepository->find($userToActivate);
            if ($user->getActive()){
                $user->setActive(false);
            }else{
                $user->setActive(true);
            }
            $entityManager->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ProfileRepository $profileRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/user/edit", name="user_edit")
     */
    public function userEdit(CustomerRepository $customerRepository,Request $request, UserRepository $userRepository, ProfileRepository $profileRepository,EntityManagerInterface $entityManager,UploaderHelper $uploaderHelper){
        if($request->isMethod('GET')){
            $user = $userRepository->find($request->query->get('id'));
            $profile = $user->getProfile();
            $customers = $customerRepository->findAll();
            //dd($this->uploadsPath);
            $finder = new Finder();
            $contents = null;
            if(file_exists($this->uploadsPath.'/user/edit/'.$user->getId())){
                $finder->in($this->uploadsPath.'/user/edit/'.$user->getId()."/*" );
                foreach ($finder as $directory) {
                    //dump($directory->getPath()."/".$directory->getFilename());
                    $contents[] = array('pfad' => $directory->getRealPath(),'datei' =>$directory->getFilename());
                }
            }

            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'profile' => $profile,
                'files' => $contents,
                'customers' => $customers
            ]);
        }
        if($request->isMethod('POST')){

            if ($request->request->get('typ')=="profile"){
                $profile = $profileRepository->find($request->request->get('id'));
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
            }elseif ($request->request->get('typ')=="user"){
                $ps = $request->request->get('provstufe');
//dd($request);
                $user = $userRepository->find($request->request->get('id'));
                $uploadedFile = $request->files->get("userImage");
                $uploadedLogo = $request->files->get("userLogo");

                if ($uploadedFile){
                    $profileImage = $uploaderHelper->uploadProfileImage($uploadedFile,$user->getId());
                    $profile = $user->getProfile();
                    $profile->setImage($profileImage);
                    $entityManager->flush();
                }
                if ($uploadedLogo){
                    $profileImage = $uploaderHelper->uploadProfileImage($uploadedLogo,$user->getId());
                    $profile = $user->getProfile();
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
                $user->setAllowedCustomer($request->request->get('allowedCustomer'));
                $user->setDisplayName($request->request->get('displayname'));
                $user->setEmail($request->request->get('email'));
                $user->setProvstufe(intval($ps));
                $user->setRoles($request->request->get('rollen'));
                //dd($request->request->get('rollen'));
                $entityManager->flush();
            }

        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param UserRepository $userRepository
     * @param ProfileRepository $profileRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @Route("user/add", name="user_add")
     */
    public function newUser(UploaderHelper $uploaderHelper,UserPasswordEncoderInterface $passwordEncoder,UserRepository $userRepository, ProfileRepository $profileRepository,EntityManagerInterface $entityManager,Request $request){

        if($request->isMethod('POST')){
            $ps = $request->request->get('provstufe');
            $uploadedFile = $request->files->get("userImage");
            $uploadedLogo = $request->files->get("userLogo");
            $newuser = new User();
            $newprofile = new Profile();
            $parentUser = $userRepository->find($request->request->get('parentuser'));
            $newuser->setActive(0);
            $newuser->setConfirmed(0);
            $newuser->setCreated(new \DateTimeImmutable());
            $newuser->setDeleted(0);
            $newuser->setDisplayName($request->request->get('displayname'));
            $newuser->setEmail($request->request->get('email'));
            $newuser->setProvstufe(intval($ps));
            $newuser->setPassword($passwordEncoder->encodePassword($newuser, $request->request->get('newpass')));
            $newuser->setParentID($parentUser->getId());
            $newuser->setProfile($newprofile);
            $newuser->setRoles($request->request->get('rollen'));
            $newprofile->setBank($request->request->get('Bank'));
            $newprofile->setBic($request->request->get('BIC'));
            $newprofile->setIban($request->request->get('IBAN'));
            $newprofile->setFirma($request->request->get('Firma'));
            $newprofile->setFinanzamt($request->request->get('Finanzamt'));
            $newprofile->setFirstName($request->request->get('Vorname'));
            $newprofile->setLastName($request->request->get('Nachname'));
            $newprofile->setOrt($request->request->get('Ort'));
            $newprofile->setPlz($request->request->get('PLZ'));
            $newprofile->setStrassenr($request->request->get('Strasse'));
            $newprofile->setTelefon($request->request->get('Telefon'));
            $newprofile->setSteuernummer($request->request->get('Steuernummer'));
            $newprofile->setSignatur($request->request->get('Signatur'));
            $entityManager->persist($newuser,$newprofile);
            $entityManager->flush();
            if ($uploadedFile){
                $profileImage = $uploaderHelper->uploadProfileImage($uploadedFile,$newuser->getId());
                $newprofile->setImage($profileImage);
                $entityManager->flush();
            }
            if ($uploadedLogo){
                $profileImage = $uploaderHelper->uploadProfileImage($uploadedLogo,$newuser->getId());
                $newprofile->setLogo($profileImage);
                $entityManager->flush();
            }
            //dd($newuser->getId());
        }

        $users = $userRepository->findBy(
            array(),
            array('displayName' => 'DESC')
        );
        return $this->render("user/add.html.twig",[
            'users' => $users
            ]
        );
    }
}
