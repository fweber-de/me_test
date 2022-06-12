<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/events', name: 'api_events_')]
class ApiEventController extends AbstractController
{
    #[Route('/', name: 'collection', methods: ['GET'])]
    public function collection(Request $request, EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->json($events, 200);
    }

    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(Request $request, EventRepository $eventRepository, int $id): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        return $this->json($event, 200);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request, EventRepository $eventRepository, EventService $eventService): Response
    {
        return $this->json([], 200);
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
