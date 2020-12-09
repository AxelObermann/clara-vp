<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\DeliveryPlaceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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
     * @Route ("deliverPlace_move_customer")
     */
    public function moveDPCustomer(CustomerRepository $customerRepository, DeliveryPlaceRepository $deliveryPlaceRepository, Request $request, EntityManagerInterface $entityManager){
        //dd($request);
        $dplace = $deliveryPlaceRepository->find($request->get('dlid'));
        $toCustomer = $customerRepository->find($request->get('toCustomer'));
        $dplace->setCustomer($toCustomer);
        $entityManager->flush();
        $this->addFlash('success', 'Die Lieferstelle wurde verschoben');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route("createAutoToDo")
     */
    public function createAutomatedToDo(DeliveryPlaceRepository $deliveryPlaceRepository){
        $heute = new \DateTime();
        $date = new \DateTime();
        $interval = new \DateInterval('P21D');

        $date->add($interval);
        $maketodos = $deliveryPlaceRepository->findBy(array('stab' => $date));

        dump($heute->format('Y-m-d'));
        dump($date->format('Y-m-d'));
        dump($maketodos);
        die();
        return new JsonResponse($heute->format('Y-m-d'));

    }
}
