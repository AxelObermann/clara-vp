<?php

namespace App\Controller;

use App\Entity\DeliverPlaceCheck;
use App\Repository\CustomerRepository;
use App\Repository\DeliverPlaceCheckRepository;
use App\Repository\DeliveryPlaceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
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

    /**
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @param DeliverPlaceCheckRepository $checkRepository
     * @param Request $request
     * @Route ("deliverPlace/new/check", name="deliverplace_new_check")
     */
    public function addNewCheck(DeliveryPlaceRepository $deliveryPlaceRepository, DeliverPlaceCheckRepository $checkRepository,Request $request,EntityManagerInterface $entityManager){
        $rp = [];
        $message="";
        if ($content = $request->getContent()) {
            $rp = json_decode($content, true);
        }
        $user = $this->getUser();
        $deplace = $deliveryPlaceRepository->find($rp['dlcheckid']);
        $check = new DeliverPlaceCheck();
        $check->setCreated(new \DateTime());
        $check->setDatum(new \DateTime($rp['checkdate']));
        $check->setWert($rp['checkwert']);
        $check->setDeliveryPlace($deplace);
        $entityManager->persist($check);
        $entityManager->flush();
        $message = "Die Daten wurden übernommen!";
        return new JsonResponse($message);


    }
}
