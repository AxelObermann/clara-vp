<?php


namespace App\Service;

use App\Entity\Messages;
use App\Entity\Notification;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Date;


class MessagesService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $em,UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function sendAdminTodoControlMessage(Notification $notification){
        dump($notification);
        $admins = $this->em->getRepository(User::class)->findAdminUsers();
        foreach ($admins as $admin){
            $reciver = $this->userRepository->find($admin['id']);
            $transm = $this->userRepository->findOneBy(array('email' => 'system@system.com'));
            $mes = new Messages();
            $mes->setCreated(new \DateTime());
            $mes->setMarkRead(0);
            $mes->setMessageType(0);
            $mes->setSubject('Ein neues Todo wurde erzeugt');
            $mes->setMessage($notification->getDescription());
            $mes->setReceiver($reciver);
            $mes->setTransmitter($transm);
            $this->em->persist($mes);
            $this->em->flush();
            //dump($admin);
        }
        return true;


    }

    public function sendAdminFacUploadControlMessage(Notification $notification){
        //dump($notification);
        $admins = $this->em->getRepository(User::class)->findAdminUsers();
        foreach ($admins as $admin){
            $reciver = $this->userRepository->find($admin['id']);
            $transm = $this->userRepository->findOneBy(array('email' => 'system@system.com'));
            $mes = new Messages();
            $mes->setCreated(new \DateTime());
            $mes->setMarkRead(0);
            $mes->setMessageType(0);
            $mes->setSubject('Neue Datei wurde hocheladen');
            $mes->setMessage($notification->getDescription());
            $mes->setReceiver($reciver);
            $mes->setTransmitter($transm);
            $this->em->persist($mes);
            $this->em->flush();
            //dump($admin);
        }
        return true;
    }
}