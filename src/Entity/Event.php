<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"review_get", "event_get", "picture_get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string",unique=true, length=255, nullable=true)
     * @Groups({"review_get", "event_get", "picture_get"})
     */
    private $setlistId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"review_get", "event_get"})
     */
    private $venue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"review_get", "event_get"})
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"review_get", "event_get"})
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Band::class, inversedBy="events")
     * @Groups({"review_get", "event_get", "picture_get"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $band;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="events")
     * @Groups({"review_get", "event_get"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="event")
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="event")
     */
    private $pictures;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="events")
     */
    private $users;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSetlistId(): ?string
    {
        return $this->setlistId;
    }

    public function setSetlistId(?string $setlistId): self
    {
        $this->setlistId = $setlistId;

        return $this;
    }

    public function getVenue(): ?string
    {
        return $this->venue;
    }

    public function setVenue(?string $venue): self
    {
        $this->venue = $venue;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getBand(): ?Band
    {
        return $this->band;
    }

    public function setBand(?Band $band): self
    {
        $this->band = $band;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setEvent($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getEvent() === $this) {
                $review->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setEvent($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getEvent() === $this) {
                $picture->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addEvent($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeEvent($this);
        }

        return $this;
    }
}
