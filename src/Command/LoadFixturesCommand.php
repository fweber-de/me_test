<?php

namespace App\Command;

use App\Entity\Contact;
use App\Entity\Event;
use App\Entity\EventLocation;
use App\Repository\EventRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * LoadFixturesCommand
 *
 * @author Florian Weber <git@fweber.info>
 */
#[AsCommand(
    name: 'app:load-fixtures',
    description: 'Adds some basic fixtures for testing',
)]
class LoadFixturesCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var EventRepository
     */
    private EventRepository $eventRepository;

    public function __construct(EntityManagerInterface $entityManager, EventRepository $eventRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $events = $this->eventRepository->findAll();

        if(count($events) > 0) {
            $io->error('fixtures will only be loaded if no events are found in the database');

            return Command::FAILURE;
        }

        //event 1
        $contacts = new ArrayCollection();
        $contacts->add((new Contact())->setEmail('ronny@sektor.de'));
        $contacts->add((new Contact())->setEmail('pepe@sektor.de'));

        $event = (new Event())
            ->setTitle('Summernight Inferno 2022')
            ->setDescription('Das Heisse Metal Event der Superklasse')
            ->setStartDate(new DateTime('2022-08-05 19:00'))
            ->setEndDate(new DateTime('2022-08-06 03:00'))
            ->setLocation((new EventLocation())
                ->setVenue('Sektor Evolution')
                ->setCity('Dresden')
                ->setStreet('An der Eisenbahn 2')
                ->setZipcode('01169')
            )
            ->setContacts($contacts)
        ;

        $this->entityManager->persist($event);

        //event 2
        $contacts = new ArrayCollection();
        $contacts->add((new Contact())->setEmail('peter@jauchnitzner.de'));

        $event = (new Event())
            ->setTitle('Jauchnitzner Sommerfest 2022')
            ->setDescription('Premium Blended Beer - Das Familien Sommerfest')
            ->setStartDate(new DateTime('2022-07-05 13:00'))
            ->setEndDate(new DateTime('2022-07-05 22:00'))
            ->setLocation((new EventLocation())
                ->setVenue('Jauchnitzner HQ')
                ->setCity('Dresden')
                ->setStreet('Bergmannstrasse 23')
                ->setZipcode('01022')
            )
            ->setContacts($contacts)
        ;

        $this->entityManager->persist($event);

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
