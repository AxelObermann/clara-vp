<?php

namespace App\Controller;

use App\Entity\DeliverPlaceCheck;
use App\Entity\DeliveryPlace;
use App\Entity\UploadedFiles;
use App\Repository\CustomerRepository;
use App\Repository\DeliverPlaceCheckRepository;
use App\Repository\DeliveryPlaceRepository;
use App\Repository\SupplierRepository;
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
     * @param Request $request
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @param CustomerRepository $customerRepository
     * @param EntityManagerInterface $entityManager
     * @Route ("/kundenindex/dp/edit/{id}")
     */
    public function editDeliveryPlace(UploadedFilesRepository $uploadedFilesRepository,DeliverPlaceCheckRepository $deliverPlaceCheckRepository, Request $request, DeliveryPlaceRepository $deliveryPlaceRepository, CustomerRepository $customerRepository,EntityManagerInterface $entityManager,SupplierRepository $supplierRepository){
        //dd($request->getPathInfo());
        if($request->isMethod('POST')){
            $dplace = $deliveryPlaceRepository->find($request->get('id'));
            $dplace->setFirmenname($request->get('Firmenname'));
            $dplace->setAnrede($request->get('Anrede'));
            $dplace->setGeburtstag($request->get('Geburtstag'));
            $dplace->setVorname($request->get('Vorname'));
            $dplace->setNachname($request->get('Nachname'));
            $dplace->setStrasse($request->get('Strasse'));
            $dplace->setHausnummer($request->get('Hausnummer'));
            $dplace->setPLZ($request->get('PLZ'));
            $dplace->setOrt($request->get('Ort'));
            $dplace->setReFirma($request->get('ReFirma'));
            $dplace->setReAnrede($request->get('ReAnrede'));
            $dplace->setReVorname($request->get('ReVorname'));
            $dplace->setReNachname($request->get('ReNachname'));
            $dplace->setReStrasse($request->get('ReStrasse'));
            $dplace->setReHausnummer($request->get('ReHausnummer'));
            $dplace->setRePLZ($request->get('RePLZ'));
            $dplace->setReOrt($request->get('ReOrt'));
            $dplace->setIban($request->get('IBAN'));
            $dplace->setBic($request->get('BIC'));
            $dplace->setVorversorger($request->get('Vorversorger'));
            $dplace->setKundennummer($request->get('Kundennummer'));
            $dplace->setKundenart($request->get('Kundenart'));
            $dplace->setVerbrauch($request->get('Verbrauch'));
            $dplace->setMaloID($request->get('MaloID'));
            $dplace->setZaehlernummer($request->get('Zaehlernummer'));
            $dplace->setMeloID($request->get('MeloID'));
            $dplace->setMedium($request->get('Medium'));
            $dplace->setStab(new \DateTime($request->get('stab')));
            $dplace->setCheckdate(new \DateTime($request->get('doneUntil')));
            $dplace->setVersorger($request->get('Versorger'));
            $dplace->setTarifname($request->get('Tarifname'));
            $dplace->setTarifnummer($request->get('Tarifnummer'));
            $dplace->setVersKdNr($request->get('VersKdNr'));
            $dplace->setAbschlussprovision($request->get('Abschlussprovision'));
            $dplace->setFolgeprovM($request->get('FolgeprovM'));
            $dplace->setSpannePKwH($request->get('SpannePKwH'));
            $dplace->setAP($request->get('AP'));
            $dplace->setGP($request->get('GP'));
            $dplace->setVertragsbeginn($request->get('Vertragsbeginn'));
            $dplace->setDauer($request->get('Dauer'));
            $entityManager->flush();
            $this->addFlash('success', 'Die änderungen wurden übernommen.');
        }

        $suppliers = $supplierRepository->findAll();
        $dpl = $deliveryPlaceRepository->find($request->get('id'));
        $checks = $deliverPlaceCheckRepository->findBy(array('deliveryPlace' => $dpl,'deleted' => false));
        $ufiles = $uploadedFilesRepository->getfiles($dpl->getId());
        return $this->render('deliver_place/edit.html.twig', [
            'dpl' => $dpl,
            'suppliers' => $suppliers,
            'checks' => $checks,
            'files' => $ufiles,
        ]);
    }

    /**
     * @param Request $request
     * @param DeliveryPlaceRepository $deliveryPlaceRepository
     * @param CustomerRepository $customerRepository
     * @param EntityManagerInterface $entityManager
     * @param SupplierRepository $supplierRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/kundenindex/dp/add" , name="deliveryplace_add")
     */
    public function addNewDeliveryPlace(Request $request, DeliveryPlaceRepository $deliveryPlaceRepository, CustomerRepository $customerRepository,EntityManagerInterface $entityManager,SupplierRepository $supplierRepository){
        if($request->isMethod('POST')){
            $customer = $customerRepository->find($request->get('cid'));
            $dplace = new DeliveryPlace();
            $dplace->setCustomer($customer);
            $dplace->setDeleted(false);
            $dplace->setInbelieferung(false);
            $dplace->setFirmenname($request->get('Firmenname'));
            $dplace->setAnrede($request->get('Anrede'));
            $dplace->setGeburtstag($request->get('Geburtstag'));
            $dplace->setVorname($request->get('Vorname'));
            $dplace->setNachname($request->get('Nachname'));
            $dplace->setStrasse($request->get('Strasse'));
            $dplace->setHausnummer($request->get('Hausnummer'));
            $dplace->setPLZ($request->get('PLZ'));
            $dplace->setOrt($request->get('Ort'));
            $dplace->setReFirma($request->get('ReFirma'));
            $dplace->setReAnrede($request->get('ReAnrede'));
            $dplace->setReVorname($request->get('ReVorname'));
            $dplace->setReNachname($request->get('ReNachname'));
            $dplace->setReStrasse($request->get('ReStrasse'));
            $dplace->setReHausnummer($request->get('ReHausnummer'));
            $dplace->setRePLZ($request->get('RePLZ'));
            $dplace->setReOrt($request->get('ReOrt'));
            $dplace->setIban($request->get('IBAN'));
            $dplace->setBic($request->get('BIC'));
            $dplace->setVorversorger($request->get('Vorversorger'));
            $dplace->setKundennummer($request->get('Kundennummer'));
            $dplace->setKundenart($request->get('Kundenart'));
            $dplace->setVerbrauch($request->get('Verbrauch'));
            $dplace->setMaloID($request->get('MaloID'));
            $dplace->setZaehlernummer($request->get('Zaehlernummer'));
            $dplace->setMeloID($request->get('MeloID'));
            $dplace->setMedium($request->get('Medium'));
            $dplace->setStab(new \DateTime($request->get('stab')));
            $dplace->setCheckdate(new \DateTime($request->get('doneUntil')));
            $dplace->setVersorger($request->get('Versorger'));
            $dplace->setTarifname($request->get('Tarifname'));
            $dplace->setTarifnummer($request->get('Tarifnummer'));
            $dplace->setVersKdNr($request->get('VersKdNr'));
            $dplace->setAbschlussprovision($request->get('Abschlussprovision'));
            $dplace->setFolgeprovM($request->get('FolgeprovM'));
            $dplace->setSpannePKwH($request->get('SpannePKwH'));
            $dplace->setAP($request->get('AP'));
            $dplace->setGP($request->get('GP'));
            $dplace->setVertragsbeginn($request->get('Vertragsbeginn'));
            $dplace->setDauer($request->get('Dauer'));
            $entityManager->persist($dplace);
            $entityManager->flush();
            $this->addFlash('success', 'Die Lieferstelle wurde angelegt');
            return $this->redirect($request->headers->get('referer'));
        }
        $suppliers = $supplierRepository->findAll();
        return $this->render('deliver_place/add.html.twig', [
            'suppliers' => $suppliers,
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

        if ($rp['dpcid'] != ""){
            dd($rp['dpcid']);
        }
        $user = $this->getUser();
        $deplace = $deliveryPlaceRepository->find($rp['dlcheckid']);
        $check = new DeliverPlaceCheck();
        $check->setCreated(new \DateTime());
        $check->setDatum(new \DateTime($rp['checkdate']));
        $check->setWert($rp['checkwert']);
        $check->setDeleted(false);
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

    /**
     * @param Request $request
     * @Route ("deliverplace/getsinglecheck/{id}")
     */
    public function getSingleCheck(Request $request,UploadedFilesRepository $uploadedFilesRepository,DeliveryPlaceRepository $deliveryPlaceRepository, DeliverPlaceCheckRepository $deliverPlaceCheckRepository){
        //dd($request->get('id'));
        $dpchecks = $deliverPlaceCheckRepository->getSingleCheck($request->get('id'));
        //dd($dpchecks);
        return new JsonResponse($dpchecks);

    }

    /**
     * @param Request $request
     * @param DeliverPlaceCheckRepository $deliverPlaceCheckRepository
     * @param EntityManagerInterface $entityManager
     * @Route ("deliverplace/save")
     */
    public function dpCheckSave(Request $request, DeliverPlaceCheckRepository $deliverPlaceCheckRepository,DeliveryPlaceRepository $deliveryPlaceRepository,EntityManagerInterface $entityManager){

        $rp = [];

        $message="";
        if ($content = $request->getContent()) {
            $rp = json_decode($content, true);
        }
        $dpc = $deliverPlaceCheckRepository->find($rp['edpcid']);
        $dpc->setWert($rp['echeckwert'])
            ->setDatum(new \DateTime($rp['echeckdate']));
        $dp = $deliveryPlaceRepository->find($dpc->getDeliveryPlace());
        $dp->setCheckdate(new \DateTime($rp['echeckdate']));
        $entityManager->flush();
        $message = "Die Daten wurden übernommen!";
        return new JsonResponse($message);
    }
}
