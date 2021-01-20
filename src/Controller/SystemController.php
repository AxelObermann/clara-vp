<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Repository\DeliverPlaceCheckRepository;
use App\Repository\DeliveryPlaceRepository;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class SystemController extends AbstractController
{
    /**
     * @Route("/system", name="system")
     */
    public function index(): Response
    {
        return $this->render('system/index.html.twig', [
            'controller_name' => 'SystemController',
        ]);
    }


    /**
     * @param EntityManager $entityManager
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     * @Route ("system/syncsupplier" , name="sync_suppliers")
     */
    public function syncSupplier(EntityManagerInterface $entityManager){

        $conn = $entityManager->getConnection();
        $sql = "SELECT DISTINCT versorger from delivery_place order by versorger ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $suppliers = $stmt->fetchAllAssociative();
        foreach ( $suppliers as $supl){
            $supplier = new Supplier();
            $supplier->setName($supl['versorger']);
            $entityManager->persist($supplier);
            $entityManager->flush();
        }

        die();
        dd($suppliers);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @Route ("system/assocCheks")
     */
    public function syncChecks(EntityManagerInterface $entityManager, DeliveryPlaceRepository $deliveryPlaceRepository){
        $conn = $entityManager->getConnection();
        $depls = $deliveryPlaceRepository->findAll();
        foreach ($depls as $dpl){
            $sql = 'UPDATE deliver_place_check set versorger='.$dpl->getVersorger().' , sended=0 WHERE delivery_place_id='.$dpl->getId();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            //dump($sql);
        }
        die();
    }

    /**
     * @param SupplierRepository $supplierRepository
     * @Route ("system/assocSupp")
     */
    public function associateSuppliersWithDpls(SupplierRepository $supplierRepository,DeliveryPlaceRepository $deliveryPlaceRepository,EntityManagerInterface $entityManager){

        $supplieres = $supplierRepository->findAll();
        foreach ($supplieres as $suppl){
            $dp = $deliveryPlaceRepository->findBy(array("Versorger" => $suppl->getName()));
            foreach ($dp as $place){
                $place->setVersorger($suppl->getId());
                $entityManager->flush();
            }
        }
        die();
        return $this->render('system/index.html.twig', [
            'controller_name' => 'SystemController',
            'suppliers' => $supplieres,
            'places' => '',
        ]);

    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("system/settings", name="system_settings")
     */
    public function systemSettings(SupplierRepository $supplierRepository, DeliveryPlaceRepository $deliveryPlaceRepository,DeliverPlaceCheckRepository $deliverPlaceCheckRepository,Request $request,MailerInterface $mailer){
        if ($request->isMethod("POST")){
            $selSupps = $request->get("check");

            foreach ($selSupps as $supplier){
                $supp = $supplierRepository->find($supplier);
                $checks = $deliverPlaceCheckRepository->findBy(array('versorger' => $supp->getId()));

                if ($checks){
                    $content='<table width="100%">';
                    $content.='<tr>
                                <td>Tarif Nr.</td>
                                <td>Str.</td>
                                <td>Nr.</td>
                                <td>PLZ</td>
                                <td>Ort</td>
                                <td>Zähler Nr.</td>
                                <td>Malo</td>
                                <td>Ablesedatum</td>
                                <td>Stand</td>
                                </tr>';
                    foreach ($checks as $check){
                        $dpl = $deliveryPlaceRepository->find($check->getDeliveryPlace());
                        $content.='<tr>
                                <td>'.$dpl->getTarifnummer().'</td>
                                <td>'.$dpl->getStrasse().'</td>
                                <td>'.$dpl->getHausnummer().'</td>
                                <td>'.$dpl->getPLZ().'</td>
                                <td>'.$dpl->getOrt().'</td>
                                <td>'.$dpl->getZaehlernummer().'</td>
                                <td>'.$dpl->getMaloID().'</td>
                                <td>'.$check->getDatum()->format("d.m.Y").'</td>
                                <td>'.$check->getWert().'</td>
                                </tr>';

                        dump($check->getDeliveryPlace()->getZaehlernummer(),$supp);
                    }
                    $content.='</table>';

                    $email = (new TemplatedEmail())
                        ->from('vp@energie-ew.de')
                        ->to($supp->getEmail())
                        ->subject('Ablesung / Zählerstände')
                        ->htmlTemplate("email/suppliersend.html.twig")
                        ->context(["VERSORGERNAME" => $supp->getName(),'VERSORGERSTRASSE' => $supp->getStreet(),'VERSORGERPLZ' => $supp->getPlz(),'VERSORGERORT' => $supp->getOrt(),'content' => $content]);

                    $mailer->send($email);dump($checks);
                }
            }
            die();
        }
        $suppliers = $supplierRepository->findAll();
        $dps = $deliveryPlaceRepository->findAll();
        return $this->render('system/index.html.twig', [
            'suppliers' => $suppliers,
            'places' => $dps,
        ]);
    }

    /**
     * @param Request $request
     * @param Supplier $supplier
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("system/sypplier/add", name="system_supplier_add")
     */
    public function AddNewSupplier(Request $request, EntityManagerInterface $entityManager){
        //dd($request->get('name'));
        $sup = new Supplier();
        $sup->setName($request->get('name'));
        $sup->setEmail($request->get('email'));
        $sup->setStreet($request->get('street'));
        $sup->setPlz($request->get('plz'));
        $sup->setOrt($request->get('ort'));
        $entityManager->persist($sup);
        $entityManager->flush();
        $this->addFlash('success', 'Der Versorger wurde angelegt.');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @Route ("system/supplier/edit", name="system_supplier_edit")
     */
    public function supplierEdit(Request $request,SupplierRepository $supplierRepository, EntityManagerInterface $entityManager){
        $supplier = $supplierRepository->find($request->get('supplierId'));
        $supplier->setEmail($request->get('email'));
        $supplier->setName($request->get('name'));
        $supplier->setStreet($request->get('street'));
        $supplier->setPlz($request->get('plz'));
        $supplier->setOrt($request->get('ort'));
        $entityManager->flush();
        $this->addFlash('success', 'Der Versorger wurde geändert.');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @Route ("system/supplier/delete/{id}")
     */
    public function supplierDelete(Request $request,SupplierRepository $supplierRepository, EntityManagerInterface $entityManager){

        $supplierRepository->deleteSupplier($request->get('id'));
        return new JsonResponse("Dir Versorger wurde glöscht");
    }
}
