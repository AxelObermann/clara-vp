<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Supplier;
use App\Entity\UploadedFiles;
use App\Entity\User;
use App\Repository\DeliveryPlaceRepository;
use App\Repository\MessagesRepository;
use App\Repository\NotificationRepository;
use App\Repository\SupplierRepository;
use App\Repository\UserRepository;
use App\Service\MessagesService;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;

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
    public function index(UserRepository $userRepository)
    {

        if ($this->isGranted("ROLE_ADMIN")){

            return $this->redirectToRoute('dashboard');
        }elseif ($this->isGranted("ROLE_USER")){
            return $this->redirectToRoute('dashboard');
        }else{
            return $this->render('default/index.html.twig', [
                'controller_name' => 'DefaultController1',
            ]);
        }
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(MessagesRepository $messagesRepository,UserRepository $userRepository, NotificationRepository $notificationRepository)
    {
        //dd($this->getUser()->getRoles());
        $this->session->set('userImage', $this->getUser()->getProfile()->getImage());
        $loggedUser = $this->getUser();
        $users = $userRepository->findBy(array('deleted' => false));
        $notifications = $notificationRepository->findTodayNotifications($loggedUser);
        $allNotifications = $notificationRepository->findBy(['toUser' => $loggedUser]);
        $messages = $messagesRepository->findBy(array('receiver' => $loggedUser,'markRead'=>0));
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_PORTAL_USER') || $this->isGranted('ROLE_FACILITY_MANAGER') ){
            return $this->render('default/dashboard.html.twig',[
                'users' => $users,
                'loggeduser' => $loggedUser,
                'notifications' => $notifications,
                'messages' => $messages,
                'allNotifications' => $allNotifications,
            ]);
        }elseif ($this->isGranted('ROLE_FACILITY')){
            return $this->redirectToRoute('facilityDashboard',[
                'messages' => $messages,
            ]);
        }
        //dd($notifications);
        return $this->render('default/dashboard.html.twig',[

            'users' => $users,
            'loggeduser' => $loggedUser,
            'notifications' => $notifications,
            'allNotifications' => $allNotifications,
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

    /**
     * @param Request $request
     * @Route ("/file_upload", name="fileUpload")
     */
    public function fileUpload(Request $request){
        dd($request);
        $uploadedFile = $request->files->get("test");
        //dump($uploadedFile);
        return $this->redirect($request->headers->get('referer'));
        return new JsonResponse($uploadedFile);
    }

    /**
     * @param Request $request
     * @Route ("/file_upload_ajax", name="fileUploadAjax")
     */
    public function fileUploadAjax(Request $request,UploaderHelper $uploaderHelper){
        //dd($request);
        $uploadedFile = $request->files->get("test");
        $test='';
        if($request->request->get('uploadPath') == '/user/edit'){
            $upp = $request->request->get('uploadPath').'/'.$request->request->get('Userid').'/'.$request->request->get('uploaddir');
            $test = $uploaderHelper->uploadAjaxFile($uploadedFile, $upp);
        }else{
            $test = $uploaderHelper->uploadAjaxFile($uploadedFile, $request->request->get('uploadPath'));
        }
        //dd($request->request->get('uploadPath')."/".$test);
//        return $this->redirect($request->headers->get('referer'));
        return new JsonResponse($test);
    }

    /**
     * @param Request $request
     * @param UploaderHelper $uploaderHelper
     * @Route ("upload/facility/dashboard")
     */
    public function uploadFacFromDashboard(MessagesService $messagesService, DeliveryPlaceRepository $deliveryPlaceRepository,Request $request, UploaderHelper $uploaderHelper,NotificationRepository $notificationRepository,EntityManagerInterface $entityManager){

        //dd($uploaderHelper->uploadsPath);
        $noti = $notificationRepository->find($request->get('notiId'));
        $dp = $deliveryPlaceRepository->find($noti->getDelveryPlace());
        $fn = "";
        $today = new \DateTime();
        $fn .= $today->format('Y')."-ZF-";

        if ($dp->getMedium()=="fa-flash"){
            $fn .= "STROM-";
        }elseif ($dp->getMedium()=="fa-fire"){
            $fn .= "GAS-";
        }elseif ($dp->getMedium()=="fa-fire-extinguisher"){
            $fn .= "XXX-";
        }
        $fn.= $dp->getTarifnummer();
        //dd($noti,$dp->getTarifnummer(), $fn );

        $uploadedFile = $request->files->get("file");
        $dplace = $deliveryPlaceRepository->find($noti->getDelveryPlace()->getId());
        $test = $uploaderHelper->uploadFacilityFile($uploadedFile,"/DP/".$noti->getDelveryPlace()->getId()."/" , $fn);
        $ufile = new UploadedFiles();
        $ufile->setDeliveryPlace($dplace);
        $ufile->setUploaded(new \DateTime());
        $ufile->setFile("/UPLOADS/DP/".$noti->getDelveryPlace()->getId()."/".$test);
        $ufile->setActive(1);
        $entityManager->persist($ufile);
        $test = $messagesService->sendAdminFacUploadControlMessage($noti);
        $entityManager->flush();
        return new JsonResponse("Die  Datei wurde erfolreich gespeichert");

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("system/settings", name="system_settings")
     */
    public function systemSettings(SupplierRepository $supplierRepository){
        $suppliers = $supplierRepository->findAll();
        return $this->render('system/index.html.twig', [
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * @param Request $request
     * @param Supplier $supplier
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("system/sypplier/add", name="system_supplier_add")
     */
    public function AddNewSupplier(Request $request, EntityManagerInterface $entityManager){
        //dd($request->get('name'));
        $sup = new Supplier();
        $sup->setName($request->get('name'));
        $sup->setEmail($request->get('email'));
        $entityManager->persist($sup);
        $entityManager->flush();
        $this->addFlash('success', 'Der Versorger wurde angelegt.');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @Route ("system/supplier/edit", name="system_supplier_edit")
     */
    public function supplierEdit(Request $request,SupplierRepository $supplierRepository, EntityManagerInterface $entityManager){
        $supplier = $supplierRepository->find($request->get('supplierId'));
        $supplier->setEmail($request->get('email'));
        $supplier->setName($request->get('name'));
        $entityManager->flush();
        $this->addFlash('success', 'Der Versorger wurde geändert.');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @Route ("system/supplier/delete/{id}")
     */
    public function supplierDelete(Request $request,SupplierRepository $supplierRepository, EntityManagerInterface $entityManager){

       $supplierRepository->deleteSupplier($request->get('id'));
        return new JsonResponse("Dir Versorger wurde glöscht");
    }
}
