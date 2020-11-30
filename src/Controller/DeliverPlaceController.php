<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\DeliveryPlaceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @Route ("deliverPlace_move_customer/{id}")
     */
    public function moveDPCustomer(CustomerRepository $customerRepository, DeliveryPlaceRepository $deliveryPlaceRepository, Request $request, EntityManagerInterface $entityManager){
        $params = $request->get('id');

        $params = explode(',', $params);
        dd($params);

    }
}
