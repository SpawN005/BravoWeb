<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique:true)]
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s]*$/',
        message: 'Le titre ne peut pas contenir des caractères spéciaux'
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La description ne peut pas être vide')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s]*$/',
        message: 'La description ne peut pas contenir des caractères spéciaux'
    )]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $nb_placeMax = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThan(propertyPath: "date_end", message: "La date de début doit être inférieure à la date de fin")]
    private ?\DateTimeInterface $date_beg = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?EventCategorie $categorie = null;

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
}
