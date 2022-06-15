<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Event;
use App\Entity\EventLocation;
use App\Repository\EventRepository;
use App\Service\EventService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/events', name: 'api_events_')]
class ApiEventController extends ApiController
{
    /**
     * @param EventRepository $eventRepository
     * @return Response
     */
    #[Route('/', name: 'collection', methods: ['GET'])]
    public function collection(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->json($events);
    }

    /**
     * @param EventRepository $eventRepository
     * @param int $id
     * @return Response
     */
    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(EventRepository $eventRepository, int $id): Response
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

        //parse the provided json data on the lowest possible level -> must bei syntactically correct
        //todo: add json schema validation later?
        if(!$this->isValidJson($json)) {
            throw new InvalidArgumentException('json not valid');
        }

        $data = json_decode($json);

        //handle contacts
        //contacts might be optional on initial creation of the event and should be added later
        $contacts = new ArrayCollection();

        if(isset($data->contacts) && @count($data->contacts) > 0) {
            foreach($data->contacts as $_contact) {
                $contact = (new Contact())
                    ->setEmail($_contact->email)
                ;

                $contacts->add($contact);
            }
        }

        //handle location data
        //as well as contacts, location data might not be available on event creation
        if(isset($data->location)) {
            $location = (new EventLocation())
                ->setCity($data->location->city)
                ->setStreet($data->location->street)
                ->setVenue($data->location->venue)
                ->setZipcode($data->location->zipcode)
            ;
        } else {
            $location = null;
        }

        //after all data is gathered create the event object
        $event = (new Event())
            ->setDescription($data->description)
            ->setTitle($data->title)
            ->setStartDate(new DateTime($data->start_date))
            ->setEndDate(new DateTime($data->end_date))
            ->setContacts($contacts)
            ->setLocation($location)
        ;

        //create the event in the database
        //via a service to provide additional checks on the domain, those wont be based on the delivery medium, json in this case
        $event = $eventService->create($event);

        return $this->json($event);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    public function update(Request $request, EventRepository $eventRepository, EventService $eventService, int $id): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        return $this->json($event, 200);
    }

    /**
     * @param EventRepository $eventRepository
     * @param EventService $eventService
     * @param int $id
     * @return Response
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(EventRepository $eventRepository, EventService $eventService, int $id): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        if(!$event) {
            throw $this->createNotFoundException('event not found');
        }

        $eventService->delete($event);

        return $this->json(null);
    }
}
