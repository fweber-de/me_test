<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['event_collection', 'event_detail'])]
    private int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(['event_collection', 'event_detail'])]
    #[Assert\NotBlank]
    private string $title;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    #[Groups(['event_detail'])]
    private string $description;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime')]
    #[Groups(['event_collection', 'event_detail'])]
    private DateTime $start_date;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime')]
    #[Groups(['event_collection', 'event_detail'])]
    private DateTime $end_date;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: 'Contact', inversedBy: 'events', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'events_contacts')]
    #[Groups(['event_detail'])]
    private Collection $contacts;

    /**
     * @var EventLocation|null
     */
    #[ORM\ManyToOne(targetEntity: 'EventLocation', cascade: ['persist'], inversedBy: 'events')]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id')]
    #[Groups(['event_collection', 'event_detail'])]
    private ?EventLocation $location;

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
     * @return Collection
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    /**
     * @param Collection $contacts
     * @return Event
     */
    public function setContacts(Collection $contacts): Event
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * @return EventLocation|null
     */
    public function getLocation(): ?EventLocation
    {
        return $this->location;
    }

    /**
     * @param EventLocation|null $location
     * @return Event
     */
    public function setLocation(?EventLocation $location): Event
    {
        $this->location = $location;
        return $this;
    }
}
