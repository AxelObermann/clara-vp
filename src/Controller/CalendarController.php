<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Repository\CalendarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar")
     */
    public function index()
    {

        return $this->render('calendar.html.twig', [

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
}
