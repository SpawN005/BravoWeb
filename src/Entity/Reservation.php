<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("reservations")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Event $id_event = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]

    private ?User $id_participant = null;

    #[ORM\Column]
    #[Groups("reservations")]
    private ?bool $isConfirmed = null;

    #[ORM\Column(type: "integer")]
    #[Assert\Range(min: 1, max: 10)]
    #[Groups("reservations")]
    private ?int $nb_place = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEvent(): ?Event
    {
        return $this->id_event;
    }

    public function setIdEvent(?Event $id_event): self
    {
        $this->id_event = $id_event;

        return $this;
    }

    public function getIdParticipant(): ?User
    {
        return $this->id_participant;
    }

    public function setIdParticipant(?User $id_participant): self
    {
        $this->id_participant = $id_participant;

        return $this;
    }

    public function isIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    public function setNbPlace(int $nb_place): self
    {
        $this->nb_place = $nb_place;

        return $this;
    }
}
