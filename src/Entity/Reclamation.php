<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 */
#[ORM\Table(name: 'reclamation')]
#[ORM\Index(name: 'ownerID', columns: ['ownerID'])]
#[ORM\Entity]
class Reclamation
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
     * @var string
     *
     */
    #[ORM\Column(name: 'title', type: 'string', length: 30, nullable: false)]
    private $title;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'description', type: 'string', length: 100, nullable: false)]
    private $description;

    /**
     * @var \DateTime
     *
     */
    #[ORM\Column(name: 'date_creation', type: 'date', nullable: false)]
    private $dateCreation;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'etat', type: 'string', length: 30, nullable: false)]
    private $etat;

    /**
     * @var \DateTime
     *
     */
    #[ORM\Column(name: 'date_treatment', type: 'date', nullable: false)]
    private $dateTreatment;

    /**
     * @var int
     *
     */
    #[ORM\Column(name: 'note', type: 'integer', nullable: false)]
    private $note;

    /**
     * @var \User
     *
     */
    #[ORM\JoinColumn(name: 'ownerID', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private $ownerid;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateTreatment(): ?\DateTimeInterface
    {
        return $this->dateTreatment;
    }

    public function setDateTreatment(\DateTimeInterface $dateTreatment): self
    {
        $this->dateTreatment = $dateTreatment;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getOwnerid(): ?User
    {
        return $this->ownerid;
    }

    public function setOwnerid(?User $ownerid): self
    {
        $this->ownerid = $ownerid;

        return $this;
    }


}
