<?php

namespace App\Service;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

/**
 * EventService
 *
 * @author Florian Weber <git@fweber.info>
 */
class EventService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Event $event
     * @return Event
     */
    public function create(Event $event): Event
    {
        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }
}
