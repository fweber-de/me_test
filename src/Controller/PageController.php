<?php

namespace App\Controller;

use App\Repository\EventRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * PageController
 *
 * @author Florian Weber <git@fweber.info>
 */
class PageController extends AbstractController
{
    /**
     * @param EventRepository $eventRepository
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findUpcoming(new DateTime());

        return $this->render('page/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @param EventRepository $eventRepository
     * @param $id
     * @return Response
     */
    #[Route('/event/{id}', name: 'detail')]
    public function detail(EventRepository $eventRepository, $id): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        if(!$event) {
            throw $this->createNotFoundException('event not found');
        }

        return $this->render('page/detail.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/calendar', name: 'calendar')]
    public function calendar(): Response
    {
        $apipath = $this->generateUrl('api_events_collection');

        return $this->render('page/calendar.html.twig', [
            'apipath' => $apipath,
        ]);
    }
}
