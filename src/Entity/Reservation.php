<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 */
#[ORM\Table(name: 'reservation')]
#[ORM\Index(name: 'fk_id_participant', columns: ['id_participant'])]
#[ORM\Index(name: 'fk_event', columns: ['id_event'])]
#[ORM\Entity]
class Reservation
{
    /**
     * @var int
     *
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var bool
     *
     */
    #[ORM\Column(name: 'isConfirmed', type: 'boolean', nullable: false)]
    private $isconfirmed;

    /**
     * @var int
     *
     */
    #[ORM\Column(name: 'nb_place', type: 'integer', nullable: false)]
    private $nbPlace;

    /**
     * @var \Event
     *
     */
    #[ORM\JoinColumn(name: 'id_event', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Event')]
    private $idEvent;

    /**
     * @var \User
     *
     */
    #[ORM\JoinColumn(name: 'id_participant', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private $idParticipant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsconfirmed(): ?bool
    {
        return $this->isconfirmed;
    }

    public function setIsconfirmed(bool $isconfirmed): self
    {
        $this->isconfirmed = $isconfirmed;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    public function getIdEvent(): ?Event
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Event $idEvent): self
    {
        $this->idEvent = $idEvent;

        return $this;
    }

    public function getIdParticipant(): ?User
    {
        return $this->idParticipant;
    }

    public function setIdParticipant(?User $idParticipant): self
    {
        $this->idParticipant = $idParticipant;

        return $this;
    }


}
