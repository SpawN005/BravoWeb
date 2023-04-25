<?php

namespace App\Entity;

use repository;
use App\Entity\User;
use App\Entity\Categorie;
use App\Repository\DonationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Table(name: 'donation')]
#[ORM\Index(name: 'owner', columns: ['owner'])]
#[ORM\Index(name: 'FK_donationcat', columns: ['categorie'])]
#[ORM\Entity]
class Donation
{
   
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

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
     * @var \DateTime
     *
     */
    #[ORM\Column(name: 'date_expiration', type: 'date', nullable: false)]
    private $dateExpiration;

    /**
     * @var int
     *
     */
    #[ORM\Column(name: 'amount', type: 'integer', nullable: false)]
    private $amount;

    /**
     * @var \User
     *
     */
    #[ORM\JoinColumn(name: 'owner', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private $owner;

    /**
     * @var \Categorie
     *
     */
    #[ORM\JoinColumn(name: 'categorie', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Categorie')]
    private $categorie;

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

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeInterface $dateExpiration): self
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


}
