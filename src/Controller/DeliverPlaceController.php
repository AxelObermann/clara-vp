<?php

namespace App\Controller;

use App\Entity\DeliverPlaceCheck;
use App\Entity\UploadedFiles;
use App\Repository\CustomerRepository;
use App\Repository\DeliverPlaceCheckRepository;
use App\Repository\DeliveryPlaceRepository;
use App\Repository\UploadedFilesRepository;
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
        $deplace->setCheckdate($check->getDatum());
        $entityManager->persist($check,$deplace);
        $entityManager->flush();
        $message = "Die Daten wurden übernommen!";
        return new JsonResponse($message);
    }

    /**
     * @param Request $request
     * @Route ("deliverplace/getUploadedFiles/{id}")
     */
    public function getUploadedFiles(Request $request,UploadedFilesRepository $uploadedFilesRepository,DeliveryPlaceRepository $deliveryPlaceRepository){
        //dd($request->get('id'));
        $dplace = $deliveryPlaceRepository->find($request->get('id'));
        $ufiles = $uploadedFilesRepository->getfiles($request->get('id'));
        //dd($ufiles);
        return new JsonResponse($ufiles);

    }
    /**
     * @param Request $request
     * @Route ("deliverplace/getchecks/{id}")
     */
    public function getChecks(Request $request,UploadedFilesRepository $uploadedFilesRepository,DeliveryPlaceRepository $deliveryPlaceRepository, DeliverPlaceCheckRepository $deliverPlaceCheckRepository){
        //dd($request->get('id'));
        $dplace = $deliveryPlaceRepository->find($request->get('id'));
        $dpchecks = $deliverPlaceCheckRepository->getChecks($request->get('id'));
        //dd($ufiles);
        return new JsonResponse($dpchecks);

    }
}
