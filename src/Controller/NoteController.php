<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\CustomerRepository;
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
}
