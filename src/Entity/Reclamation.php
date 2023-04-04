<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Reclamation
 *
 */
#[ORM\Table(name: 'reclamation')]
#[ORM\Index(name: 'ownerID', columns: ['ownerID'])]
#[ORM\Entity]
#[UniqueEntity(fields:["title", "description"], message:"Cette réclamation existe déjà.")]

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
    #[Assert\NotBlank(message:"Le champ Titre ne peut pas être vide")]
    #[Assert\Length(max:10, maxMessage:"Le champ Titre ne peut pas contenir plus de {{ 10 }} caractères")]
    #[Assert\Regex(pattern:"/^[a-zA-Z0-9 ]*$/", message:"Le champ Titre ne peut contenir que des lettres, des chiffres et des espaces")]
    private $title;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'description', type: 'string', length: 100, nullable: false)]
    #[Assert\NotBlank(message:"Le champ Description ne peut pas être vide")]
    #[Assert\Regex(pattern:"/^[a-zA-Z0-9 ]*$/", message:"Le champ Description ne peut contenir que des lettres, des chiffres et des espaces")]
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
    #[ORM\Column(name: 'date_treatment', type: 'date', nullable: true)]
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

    #[ORM\JoinColumn(name: 'typereclamation', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Typereclamation')]
    private $typereclamation;

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
    public function __toString(): string
{
    return sprintf("Reclamation #%d: %s\nDescription: %s\nDate de création: %s\nEtat: %s\nDate de traitement: %s\nNote: %d\nPropriétaire: %s",
        $this->id,
        $this->title,
        $this->description,
        $this->dateCreation->format('Y-m-d'),
        $this->etat,
        $this->dateTreatment ? $this->dateTreatment->format('Y-m-d') : 'N/A',
        $this->note,
        $this->ownerid ? $this->ownerid->getFirstname() : 'Anonyme'
    );
}

    public function getTypereclamation(): ?Typereclamation
    {
        return $this->typereclamation;
    }

    public function setTypereclamation(?Typereclamation $typereclamation): self
    {
        $this->typereclamation = $typereclamation;

        return $this;
    }


}
