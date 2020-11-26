<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeliverPlaceController extends AbstractController
{
    /**
     * @Route("/deliver/place", name="deliver_place")
     */
    public function index()
    {
        return $this->render('deliver_place/index.html.twig', [
            'controller_name' => 'DeliverPlaceController',
        ]);
    }
}
