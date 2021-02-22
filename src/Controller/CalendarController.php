<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Repository\CalendarRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar")
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        return $this->render('calendar/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @Route ("/calGetTodayEvents", name="cal_get_today_events")
     */
    public function getTodayEvents(EntityManagerInterface $entityManager){
        $user = $this->getUser();
        $events = $this->getDoctrine()->getRepository(Calendar::class)->findTodayEvents($user);
        $data[]=array();
        foreach($events as $event) {
            //dd($event);
            //echo "**".$event->getStart()->format("Y-m-d H:i:s");
            if($event->getAllday() == 1) {
                $allday = true;
                $end = $event->getStart()->format("Y-m-d H:i:s");
            } else {
                $allday = false;
                $end = $event->getEnd()->format("Y-m-d H:i:s");
            }
            $data[] = array(
                'id' => $event->getId(),
                'title'=>$event->getTitle(),
                'start'=>$event->getStart()->format("Y-m-d H:i:s"),
                'end' => $end,
                'allDay' => $allday,
                'details' => $event->getDetails(),
                'Kundenname' => $event->getCustomerName(),
                'Strasse' => $event->getStreet(),
                'HausNr' => $event->getStreetNumber(),
                'PLZ' => $event->getPlz(),
                'Ort' => $event->getOrt(),
                'Ansprechpartner' => $event->getAnsprechpartner(),
                'anrede' => $event->getAnrede(),
                'Emailadresse' => $event->getEmail(),
                'Bemerkungen' => $event->getBermerkungen(),
                'backgroundColor' => $event->getColor(),
                'calTerminTppe' => $event->getTerminType()
            );
        }

        return new JsonResponse($data);
    }

    /**
     * @param CalendarRepository $calendarRepository
     * @param Request $request
     * @Route ("/calendars/getEvents")
     */
    public function getEvents(CalendarRepository $calendarRepository,Request $request)
    {
        $user = $this->getUser();
        $events = $calendarRepository->findBy(array('user' => $user));
        $data = array();
        foreach ($events as $event) {
            //dd($event);
            //echo "**".$event->start;
            if ($event->getAllday() == 1) {
                $allday = true;
                $end = $event->getStart()->format("Y-m-d H:i:s");
            } else {
                $allday = false;
                $end = $event->getEnd()->format("Y-m-d H:i:s");
            }
            $data[] = array(
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'start' => $event->getStart()->format("Y-m-d H:i:s"),
                'end' => $end,
                'eventColor' => $event->getColor(),
                'allDay' => $allday,
                'details' => $event->getDetails(),
                'Kundenname' => $event->getCustomerName(),
                'Strasse' => $event->getStreet(),
                'HausNr' => $event->getStreetNumber(),
                'PLZ' => $event->getPlz(),
                'Ort' => $event->getOrt(),
                'Ansprechpartner' => $event->getAnsprechpartner(),
                'anrede' => $event->getAnrede(),
                'Emailadresse' => $event->getEmail(),
                'Bemerkungen' => $event->getBermerkungen(),
                'backgroundColor' => $event->getColor()
            );

        }
        return new JsonResponse($data);
    }

    /**
     * @param Request $request
     * @Route ("/calendar/addNewEvent")
     */
    public function addNewEvent(Request $request,EntityManagerInterface $entityManager,UserRepository $userRepository){

        //dd($request);
        $newEvent = new Calendar();
        $user = $userRepository->find($this->getUser());
        $newEvent->setUser($user);
        if ( $request->get('allday') ){
            $newEvent->setAllday(true);
        }else{
            $newEvent->setAllday(false);
        }

        $newEvent->setStart(new \DateTime($request->get('starts')));
        $newEvent->setEnd(new \DateTime($request->get('ends')));
        $newEvent->setTitle($request->get('ename'));
        $newEvent->setColor($request->get('eventColorChosen'));
        $newEvent->setAnsprechpartner($request->get('Ansprechpartner'));
        $newEvent->setEmail($request->get('Emailadresse'));
        $newEvent->setStreet($request->get('Strasse'));
        $newEvent->setStreetNumber($request->get('HausNr'));
        $newEvent->setPlz($request->get('PLZ'));
        $newEvent->setOrt($request->get('Ort'));
        $newEvent->setBermerkungen($request->get('Bemerkungen'));
        $entityManager->persist($newEvent);
        $entityManager->flush();
        $this->addFlash('success','neuer Termin wurde angelegt!');
        return $this->redirectToRoute('calendar');
    }

    /**
     * @param Request $request
     * @Route ("/calendar/editEvent")
     */
    public function editEvent(Request $request,EntityManagerInterface $entityManager,CalendarRepository $calendarRepository, UserRepository $userRepository,MailerInterface $mailer)
    {


        $editEvent = $calendarRepository->find($request->get('eventid'));
        if ( $request->get('Eallday') ){
            $editEvent->setAllday(true);
        }else{
            $editEvent->setAllday(false);
        }
        if ( $request->get('terminbest') ){
            $message = "";
            //dump($editEvent->getUser()->getProfile()->getLogo());
            //dump($editEvent->getUser()->getProfile()->getSignatur());
            if($request->get('EeventColorChosen') == "#015780"){
                $message = "Bitte halten sie ihre Strom- & Erdgasrechnung/en bereit, damit wir die Berechnung ihres Einsparpotenzials erstellen können.";
                //dd("StromErdgas");
            }elseif ($request->get('EeventColorChosen') == "#f19408"){
                $message = "Wir werden zu diesem Termin uns und unsere Leistungen vorstellen, sowie die örtlichen Gegebenheiten besichtigen und notwenige Daten.";
                //dd("Solar");
            }elseif ($request->get('EeventColorChosen') == "#9463F7"){
                $message = "Bitte halten sie ihre Stromrechnung bereit, damit wir die Berechnung ihres Einsparpotenzials erstellen können.";
                //dd("MSB");
            }elseif ($request->get('EeventColorChosen') == "#000000"){
                $message = "";
                //dd("Geblockt");
            }elseif ($request->get('EeventColorChosen') == "#17B3A3"){
                $message = "Wenn wir ein Onlinemeeting vereinbart haben, nutzen sie bitte folgende Zugangsdaten.<br>".$editEvent->getBermerkungen();
                //dd("Meeting");
            }elseif ($request->get('EeventColorChosen') == "#C7C9CB"){

                //dd("Allgemein");
            }
            $email = (new TemplatedEmail())
                ->from('vp@energie-ew.de')
                ->to($editEvent->getUser()->getEmail())
                ->addCc("info@bentley-energie.de")
                ->subject('Terminbestätigung')
                ->htmlTemplate("email/terminbest.html.twig")
                ->context(['DATUM' => $editEvent->getStart()->format("d.m.Y"),'UHRZEIT' => $editEvent->getStart()->format("H:m"),"MESSAGE" => $message,'SIGNATUR' => $editEvent->getUser()->getProfile()->getSignatur(),'LOGO' => $editEvent->getUser()->getProfile()->getLogo()]);

            $mailer->send($email);
        }
        $editEvent->setTitle($request->get('Eename'));
        $editEvent->setStart(new \DateTime($request->get('Estarts')));
        $editEvent->setEnd(new \DateTime($request->get('Eends')));
        $editEvent->setColor($request->get('EeventColorChosen'));
        $editEvent->setAnsprechpartner($request->get('EAnsprechpartner'));
        $editEvent->setEmail($request->get('EEmailadresse'));
        $editEvent->setStreet($request->get('EStrasse'));
        $editEvent->setStreetNumber($request->get('EHausNr'));
        $editEvent->setPlz($request->get('EPLZ'));
        $editEvent->setOrt($request->get('EOrt'));
        $editEvent->setBermerkungen($request->get('EBemerkungen'));
        $entityManager->persist($editEvent);
        $entityManager->flush();
        $this->addFlash('success','Der Termin wurde geändert!');
        return $this->redirectToRoute('calendar');

    }

    /**
     * @param Request $request
     * @param CalendarRepository $calendarRepository
     * @Route ("/calendar/deleteEvent")
     */
    public function deleteEvent(Request $request, CalendarRepository $calendarRepository,EntityManagerInterface $entityManager){
        $editEvent = $calendarRepository->find($request->get('eventid'));
        $entityManager->remove($editEvent);
        $entityManager->flush();
        $this->addFlash('success','Der Termin wurde gelöscht!');
        return $this->redirectToRoute('calendar');
    }
}
