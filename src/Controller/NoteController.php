<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\CustomerRepository;
use App\Repository\DeliveryPlaceRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    /**
     * @Route("/note", name="note")
     */
    public function index(): Response
    {
        return $this->render('note/index.html.twig', [
            'controller_name' => 'NoteController',
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CustomerRepository $customerRepository
     * @Route ("/note/add/customernote")
     */
    public function addNewCustomerNote(Request $request,EntityManagerInterface $em, CustomerRepository $customerRepository){
        $customer = $customerRepository->find($request->get('cid'));
        $note = new Note();
        $note->setCustomer($customer);
        $note->setAuthor($this->getUser());
        $note->setDescription($request->get('description'));
        $note->setNotetext($request->get('noteText'));
        $note->setDeleted(false);
        $note->setCreated(new \DateTime());
        $em->persist($note);
        $em->flush();

        $message = "neue Notiz wurde erstellt.";
        return new JsonResponse($message);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CustomerRepository $customerRepository
     * @Route ("/note/add/dpnote")
     */
    public function addNewDeliveryPlaceNote(Request $request,EntityManagerInterface $em, CustomerRepository $customerRepository,DeliveryPlaceRepository $deliveryPlaceRepository){
        $dpl = $deliveryPlaceRepository->find($request->get('cid'));

        //dd($dpl);
        $note = new Note();
        $note->setDpl($dpl);
        $note->setAuthor($this->getUser());
        $note->setDescription($request->get('description'));
        $note->setNotetext($request->get('noteText'));
        $note->setDeleted(false);
        $note->setCreated(new \DateTime());
        $em->persist($note);
        $em->flush();

        $message = "neue Notiz wurde erstellt.";
        return new JsonResponse($message);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @Route ("/note/delete/{id}")
     */
    public function deleteNote(EntityManagerInterface $entityManager, Request $request){

        $note = $entityManager->getReference(Note::class, $request->get('id'));
        $entityManager->remove($note);
        $entityManager->flush();
        $message = "die Notiz wurde gelöscht.";
        return new JsonResponse($message);

    }
}
