<?php

namespace App\Service;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param Event $event
     * @return Event
     * @throws ValidationFailedException
     */
    public function create(Event $event): Event
    {
        //validate the entity
        $errors = $this->validator->validate($event);

        if(count($errors) > 0) {
            throw new ValidationFailedException($event, $errors);
        }

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
     * @throws ValidationFailedException
     */
    public function update(Event $event): Event
    {
        //validate the entity
        $errors = $this->validator->validate($event);

        if(count($errors) > 0) {
            throw new ValidationFailedException($event, $errors);
        }

        $this->entityManager->flush();

        return $event;
    }
}
