<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'datetime')]
    private DateTime $start_date;

    #[ORM\Column(type: 'datetime')]
    private DateTime $end_date;

    #[ORM\ManyToMany(targetEntity: 'Contact', inversedBy: 'events')]
    #[ORM\JoinTable(name: 'events_contacts')]
    private ArrayCollection $contacts;

    #[ORM\ManyToOne(targetEntity: 'EventLocation', inversedBy: 'events')]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id')]
    private EventLocation $location;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Event
     */
    public function setTitle(string $title): Event
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Event
     */
    public function setDescription(string $description): Event
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->start_date;
    }

    /**
     * @param DateTime $start_date
     * @return Event
     */
    public function setStartDate(DateTime $start_date): Event
    {
        $this->start_date = $start_date;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->end_date;
    }

    /**
     * @param DateTime $end_date
     * @return Event
     */
    public function setEndDate(DateTime $end_date): Event
    {
        $this->end_date = $end_date;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getContacts(): ArrayCollection
    {
        return $this->contacts;
    }

    /**
     * @param ArrayCollection $contacts
     * @return Event
     */
    public function setContacts(ArrayCollection $contacts): Event
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * @return EventLocation
     */
    public function getLocation(): EventLocation
    {
        return $this->location;
    }

    /**
     * @param EventLocation $location
     * @return Event
     */
    public function setLocation(EventLocation $location): Event
    {
        $this->location = $location;
        return $this;
    }
}
