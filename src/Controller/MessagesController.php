<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    /**
     * @Route("/nachrichten", name="nachrichten")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        return $this->render('messages/inbox.html.twig', [
            'title' => 'Nachrichten',
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
