<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;


class NotificationController extends AbstractController
{
    /**
     * @Route("/notification", name="notification")
     */
    public function index(NotificationRepository  $notificationRepository)
    {
        $tome = $notificationRepository->findBy(array('toUser'=> $this->getUser()->getId()));
        $fromme = $notificationRepository->findBy(array('fromUser'=> $this->getUser()->getId()));
        $allreadydone = $notificationRepository->findBy(array('seen'=> 1));
        return $this->render('notification/index.html.twig', [
            'tome' => $tome,
            'fromme' => $fromme,
            'allreadydone' => $allreadydone,
            'controller_name' => 'NotificationController',
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
            $notification->setText($request->request->get('text'));
            $notification->setSeen(false);
            $notification->setDoneUntil(new \DateTime($request->request->get('todate')));
            $notification->setToUser($toUser);
            $notification->setFromUser($fromuser);
            $notification->setLink($request->headers->get('referer'));
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
    public function createToDo(Request $request, UserRepository $userRepository, NotificationRepository $notificationRepository){

    }
}
