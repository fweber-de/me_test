<?php

namespace App\Entity;

use App\Repository\EventLocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventLocationRepository::class)]
class EventLocation
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
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['event_collection', 'event_detail'])]
    #[Assert\NotBlank]
    private string $venue;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['event_collection', 'event_detail'])]
    #[Assert\NotBlank]
    private string $street;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['event_collection', 'event_detail'])]
    #[Assert\NotBlank]
    private string $zipcode;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['event_collection', 'event_detail'])]
    #[Assert\NotBlank]
    private string $city;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: 'location', targetEntity: 'Event')]
    #[Ignore]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
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
    public function getVenue(): string
    {
        return $this->venue;
    }

    /**
     * @param string $venue
     * @return EventLocation
     */
    public function setVenue(string $venue): EventLocation
    {
        $this->venue = $venue;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return EventLocation
     */
    public function setStreet(string $street): EventLocation
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     * @return EventLocation
     */
    public function setZipcode(string $zipcode): EventLocation
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return EventLocation
     */
    public function setCity(string $city): EventLocation
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    /**
     * @param Collection $events
     * @return EventLocation
     */
    public function setEvents(Collection $events): EventLocation
    {
        $this->events = $events;
        return $this;
    }
}
