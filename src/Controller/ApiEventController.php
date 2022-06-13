<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventLocation;
use App\Repository\EventRepository;
use App\Service\EventService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/events', name: 'api_events_')]
class ApiEventController extends ApiController
{
    /**
     * @param Request $request
     * @param EventRepository $eventRepository
     * @return Response
     */
    #[Route('/', name: 'collection', methods: ['GET'])]
    public function collection(Request $request, EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->json($events);
    }

    /**
     * @param Request $request
     * @param EventRepository $eventRepository
     * @param int $id
     * @return Response
     */
    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(Request $request, EventRepository $eventRepository, int $id): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        return $this->json($event);
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     * @return Response
     * @throws Exception
     */
    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request, EventService $eventService): Response
    {
        $json = $request->getContent();

        if(!$this->isValidJson($json)) {
            throw new \InvalidArgumentException('json not valid');
        }

        $data = json_decode($json);

        $contacts = new ArrayCollection();
        $location = (new EventLocation())
            ->setCity('')
            ->setStreet('')
            ->setVenue('')
            ->setZipcode('')
        ;

        $event = (new Event())
            ->setDescription($data->description)
            ->setTitle($data->title)
            ->setStartDate(new DateTime($data->start_date))
            ->setEndDate(new DateTime($data->end_date))
            ->setContacts($contacts)
            ->setLocation($location)
        ;

        $event = $eventService->create($event);

        return $this->json($event);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    public function update(Request $request, EventRepository $eventRepository, EventService $eventService, int $id): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        return $this->json($event, 200);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, EventRepository $eventRepository, EventService $eventService, int $id): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        return $this->json(null, 200);
    }
}
