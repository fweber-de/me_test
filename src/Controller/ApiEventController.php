<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Event;
use App\Entity\EventLocation;
use App\Repository\ContactRepository;
use App\Repository\EventLocationRepository;
use App\Repository\EventRepository;
use App\Service\EventService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use InvalidArgumentException;
use Nelmio\ApiDocBundle\Model\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

/**
 * ApiEventController
 *
 * @author Florian Weber <git@fweber.info>
 */
#[Route('/api/events', name: 'api_events_')]
class ApiEventController extends ApiController
{
    /**
     * @param EventRepository $eventRepository
     * @return Response
     */
    #[Route('/', name: 'collection', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a list of all submitted events.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Event::class))
        )
    )]
    public function collection(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->json($events, 200, [], ['groups' => 'event_collection']);
    }

    /**
     * @param EventRepository $eventRepository
     * @param int $id
     * @return Response
     */
    #[Route('/{id}', name: 'read', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a detail view of a specific event.',
        content: new OA\JsonContent(
            ref: new Model(type: Event::class)
        )
    )]
    public function read(EventRepository $eventRepository, int $id): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        return $this->json($event, 200, [], ['groups' => 'event_detail']);
    }

    /**
     * @param Request $request
     * @param EventService $eventService
     * @param ContactRepository $contactRepository
     * @param EventLocationRepository $eventLocationRepository
     * @return Response
     * @throws Exception
     */
    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request, EventService $eventService, ContactRepository $contactRepository, EventLocationRepository $eventLocationRepository): Response
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
                //find existing contact
                $contact = $contactRepository->findOneBy(['email' => $_contact->email]);

                if(!$contact) {
                    $contact = (new Contact())
                        ->setEmail($_contact->email)
                    ;
                }

                $contacts->add($contact);
            }
        }

        //handle location data
        //as well as contacts, location data might not be available on event creation
        if(isset($data->location)) {
            //find existing location
            //todo: maybe extend to other criteria
            $location = $eventLocationRepository->findOneBy(['id' => $data->location->id ?? null]);

            if(!$location) {
                $location = (new EventLocation())
                    ->setCity($data->location->city)
                    ->setStreet($data->location->street)
                    ->setVenue($data->location->venue)
                    ->setZipcode($data->location->zipcode)
                ;
            }
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

    /**
     * @param Request $request
     * @param EventRepository $eventRepository
     * @param EventService $eventService
     * @param EventLocationRepository $eventLocationRepository
     * @param ContactRepository $contactRepository
     * @param int $id
     * @return Response
     * @throws Exception
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, EventRepository $eventRepository, EventService $eventService, EventLocationRepository $eventLocationRepository, ContactRepository $contactRepository, int $id): Response
    {
        //fetch the event
        $event = $eventRepository->findOneBy(['id' => $id]);

        if(!$event) {
            throw $this->createNotFoundException('event not found');
        }

        //update

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
                //find existing contact
                $contact = $contactRepository->findOneBy(['email' => $_contact->email]);

                if(!$contact) {
                    $contact = (new Contact())
                        ->setEmail($_contact->email)
                    ;
                }

                $contacts->add($contact);
            }

            $event->setContacts($contacts);
        }

        if(isset($data->contacts) && (@count($data->contacts) == 0 || $data->contacts == null)) {
            $event->setContacts(new ArrayCollection());
        }

        //handle location data
        //as well as contacts, location data might not be available on event creation
        if(isset($data->location)) {
            //find existing location
            //todo: maybe extend to other criteria
            $location = $eventLocationRepository->findOneBy(['id' => $data->location->id ?? null]);

            if(!$location) {
                $location = (new EventLocation())
                    ->setCity($data->location->city)
                    ->setStreet($data->location->street)
                    ->setVenue($data->location->venue)
                    ->setZipcode($data->location->zipcode)
                ;
            }

            $event->setLocation($location);
        }

        $event
            ->setTitle($data->title)
            ->setDescription($data->description)
            ->setStartDate(new DateTime($data->start_date))
            ->setEndDate(new DateTime($data->end_date))
        ;

        $eventService->update($event);

        return $this->json($event);
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
        //fetch the event
        $event = $eventRepository->findOneBy(['id' => $id]);

        if(!$event) {
            throw $this->createNotFoundException('event not found');
        }

        //remove the selected event
        $eventService->delete($event);

        return $this->json(null);
    }
}
