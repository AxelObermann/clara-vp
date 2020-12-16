<?php

namespace App\Controller;

use App\Repository\MessagesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    /**
     * @Route("/nachrichten", name="nachrichten")
     * @IsGranted("ROLE_USER")
     */
    public function index(MessagesRepository $messagesRepository)
    {
        $loggedUser = $this->getUser();
        $messages = $messagesRepository->findBy(array('receiver' => $loggedUser,'markRead'=>0));
        return $this->render('messages/inbox.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/nachrichten/compose", name="nachrichten_compose")
     * @IsGranted("ROLE_USER")
     */
    public function messageCompose()
    {
        return $this->render('messages/compose.html.twig', [
            'title' => 'Nachrichten',
        ]);
    }
}
