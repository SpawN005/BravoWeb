<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[UniqueEntity(fields: ['title'], message: 'There is already an event with this title')]
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("events") ]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique:true)]
    #[Groups("events") ]
    #[Assert\NotBlank(message: 'Please enter a title.')]
    #[Assert\Length(max:20, maxMessage:"The Field Title cannot cannot contain more than {{20}} caracters")]
    #[Assert\Regex(pattern:"/^[a-zA-Z0-9 ]*$/", message:"The Field Title can only contain letters and numbers")]
    private ?string $title ;

    #[ORM\Column(length: 255)]
    #[Groups("events") ]
    #[Assert\NotBlank(message: 'Please enter a description.')]
    #[Assert\Length(max:100, maxMessage:"The Field Desciption cannot cannot contain more than {{20}} caracters")]
    #[Assert\Regex(pattern:"/^[a-zA-Z0-9 ]*$/", message:"The Field Title can only contain letters and numbers")]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups("events")]
    #[Assert\GreaterThanOrEqual(value:0)]
    private ?int $nb_placeMax = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("events") ]
    private ?\DateTimeInterface $date_beg = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("events") ]
    private ?\DateTimeInterface $date_end = null;

    // public function getToday(): ?\DateTimeInterface
    // {
    //     return new \DateTime();
    // }

   


    #[ORM\Column(length: 255)]
    #[Groups("events") ]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
   
    private ?EventCategorie $categorie = null;

   

    #[ORM\OneToMany(mappedBy: 'id_event', targetEntity: Reservation::class, cascade: ['remove'])]
    
    private Collection $reservations;

    #[ORM\ManyToOne(inversedBy: 'events')]
    
    private ?User $participant = null;




    public function __construct()
    {
        $this->id_participant = new ArrayCollection();
        $this->id_event = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbPlaceMax(): ?int
    {
        return $this->nb_placeMax;
    }

    public function setNbPlaceMax(int $nb_placeMax): self
    {
        $this->nb_placeMax = $nb_placeMax;

        return $this;
    }

    public function getDateBeg(): ?\DateTimeInterface
    {
        return $this->date_beg;
    }

    public function setDateBeg(\DateTimeInterface $date_beg): self
    {
        $this->date_beg = $date_beg;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategorie(): ?EventCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(?EventCategorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    // /**
    //  * @return Collection<int, Reservation>
    //  */
    // public function getIdParticipant(): Collection
    // {
    //     return $this->id_participant;
    // }

    public function addIdParticipant(Reservation $idParticipant): self
    {
        if (!$this->id_participant->contains($idParticipant)) {
            $this->id_participant->add($idParticipant);
            $idParticipant->setIdEvent($this);
        }

        return $this;
    }

    public function removeIdParticipant(Reservation $idParticipant): self
    {
        if ($this->id_participant->removeElement($idParticipant)) {
            // set the owning side to null (unless already changed)
            if ($idParticipant->getIdEvent() === $this) {
                $idParticipant->setIdEvent(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, Reservation>
    //  */
    // public function getIdEvent(): Collection
    // {
    //     return $this->id_event;
    // }

    public function addIdEvent(Reservation $idEvent): self
    {
        if (!$this->id_event->contains($idEvent)) {
            $this->id_event->add($idEvent);
            $idEvent->setIdEvent($this);
        }

        return $this;
    }

    public function removeIdEvent(Reservation $idEvent): self
    {
        if ($this->id_event->removeElement($idEvent)) {
            // set the owning side to null (unless already changed)
            if ($idEvent->getIdEvent() === $this) {
                $idEvent->setIdEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setIdEvent($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdEvent() === $this) {
                $reservation->setIdEvent(null);
            }
        }

        return $this;
    }

    public function getParticipant(): ?User
    {
        return $this->participant;
    }

    public function setParticipant(?User $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
    





}
