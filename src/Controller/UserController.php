<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
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
}
