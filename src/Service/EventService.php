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
        //todo: add validation

        //call doctrine to persist the event data
        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }

    /**
     * @param Event $event
     * @return void
     */
    public function delete(Event $event): void
    {
        $this->entityManager->remove($event);
        $this->entityManager->flush();
    }

    /**
     * @param Event $event
     * @return Event
     */
    public function update(Event $event): Event
    {
        //todo: add validation

        $this->entityManager->flush();

        return $event;
    }
}
