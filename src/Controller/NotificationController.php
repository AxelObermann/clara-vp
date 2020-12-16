<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use http\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;


class NotificationController extends AbstractController
{
    /**
     * @Route("/notification", name="notification")
     */
    public function index(NotificationRepository  $notificationRepository, UserRepository $userRepository)
    {
        $user = $userRepository->find($this->getUser());
        $tome = $notificationRepository->findBy(array('toUser'=> $user,'seen'=> 0), array('doneUntil'=>'DESC'));
        $fromMe = $notificationRepository->findBy(array('fromUser'=> $user,'seen'=> 0), array('doneUntil'=>'DESC'));
        $allreadydone = $notificationRepository->findDoneTodos($user);
        return $this->render('notification/index.html.twig', [
            'tome' => $tome,
            'fromme' => $fromMe,
            'allreadydone' => $allreadydone,
            'controller_name' => 'NotificationController',
        ]);
    }

    /**
     * @param NotificationRepository $notificationRepository
     * @param UserRepository $userRepository
     * @Route ("facilityDashboard", name="facilityDashboard")
     */
    public function facilityDashboard(NotificationRepository  $notificationRepository, UserRepository $userRepository){
        $user = $userRepository->find($this->getUser());
        $tome = $notificationRepository->findBy(array('toUser'=> $user,'seen'=> 0), array('plz'=>'ASC'));

        return $this->render('notification/indexfac.html.twig', [
            'tome' => $tome,
        ]);
    }

    /**
     * @param Request $request
     * @Route ("/noticreatefromdashboard", name="noti_create_from_dashboard")
     */
    public function createTodoFromDashboard(Request $request, UserRepository $userRepository,EntityManagerInterface $entityManager){
        if($request->isMethod('POST')){
            //dd($request);
            $fromuser = $this->getUser();
            $toUser = $userRepository->find($request->request->get('userId'));
            $notification = new Notification();
            $notification->setDescription($request->request->get('text'));
            $notification->setText('');
            $notification->setType($request->request->get('notiType'));
            $notification->setSeen(false);
            $notification->setDoneUntil(new \DateTime($request->request->get('todate')));
            $notification->setToUser($toUser);
            $notification->setFromUser($fromuser);
            $notification->setLink($request->headers->get('referer'));
            $notification->setDone(0);
            $notification->setCreatedAt(new DateTime());
            $notification->setUpdatedAt(new DateTime());
            $entityManager->persist($notification);
            $entityManager->flush();
            //dd($request);
        }
        return $this->redirect($request->headers->get('referer'));
    }



    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param NotificationRepository $notificationRepository
     * @Route ("/createToDo", name="noti_create_global")
     */
    public function createToDo(Request $request, UserRepository $userRepository, NotificationRepository $notificationRepository,EntityManagerInterface $entityManager){
        $sysUser = $userRepository->findOneBy(array('email' => 'system@system.com'));
        $reciever = $userRepository->find($request->get('toUser'));
        $message = new Messages();
        $message->setCreated(new DateTime());
        $message->setMessage("neues Todo");
        $message->setMessageType(0);
        $message->setSubject('neues Todo');
        $message->setTransmitter($sysUser);
        $message->setReceiver($reciever);
        $message->setMarkRead(0);
        $entityManager->persist($message);
        $entityManager->flush();
        dd($request);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param NotificationRepository $notificationRepository
     * @param EntityManagerInterface $entityManager
     * @Route ("/notification/markasseen/{id}")
     */
    public function markAsSeen($id,Request $request,NotificationRepository $notificationRepository,EntityManagerInterface $entityManager){
        $noti = $notificationRepository->find($id);
        $noti->setSeen(1);
        $entityManager->flush();
        return $this->redirect($request->headers->get('referer'));
    }
}
