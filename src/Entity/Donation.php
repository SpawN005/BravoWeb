<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Donation
 *
 */
#[ORM\Table(name: 'donation')]
#[ORM\Index(name: 'owner', columns: ['owner'])]
#[ORM\Index(name: 'FK_donationcat', columns: ['categorie'])]
#[ORM\Entity]
class Donation
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
    #[Assert\NotBlank(message:'Please enter a title')]
    private $title;

    /**
     * @var string
     *
     */
    #[Assert\NotBlank(message:'Please enter a description')]
    #[ORM\Column(name: 'description', type: 'string', length: 100, nullable: false)]
    private $description;

    /**
     * @var \DateTime
     *
     */
    #[Assert\GreaterThanOrEqual('today', message:'The start date must be a date that is later than the current date.')]
    #[ORM\Column(name: 'date_creation', type: 'date', nullable: false)]
    private $dateCreation;

    /**
     * @var \DateTime
     *
     */
    #[Assert\GreaterThanOrEqual(propertyPath:'dateCreation', message:'The end date must be a date that is later than the start date.')]
    #[ORM\Column(name: 'date_expiration', type: 'date', nullable: false)]
    private $dateExpiration;

    /**
     * @var int
     *
     */
    #[Assert\Range(min: 1,notInRangeMessage: 'The amount must be greater than zero',)]
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
     * @var \CategorieDonation
     *
     */
    #[ORM\JoinColumn(name: 'categorie', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CategorieDonation')]
    private $categorie;
    #[ORM\OneToMany(targetEntity: 'Donater', mappedBy: 'idDonation')]

    private $donaters;
    public function __toString(): string
    {
        return $this->title;
    }
    public function __construct()
    {
        $this->donaters = new ArrayCollection();
    }
    public function getDonaters()
    {
        return $this->donaters;
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

    public function getCategorie(): ?CategorieDonation
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieDonation $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
