<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[UniqueEntity(fields: ['title'], message: 'There is already an event with this title')]
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Please enter a title.')]
    #[Assert\Length(max: 20, maxMessage: "The Field Title cannot cannot contain more than {{20}} caracters")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9 ]*$/", message: "The Field Title can only contain letters and numbers")]
    private ?string $title;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter a description.')]
    #[Assert\Length(max: 100, maxMessage: "The Field Desciption cannot cannot contain more than {{20}} caracters")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9 ]*$/", message: "The Field Title can only contain letters and numbers")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(value: 0)]
    private ?int $nb_placeMax = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_beg = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end = null;

    // public function getToday(): ?\DateTimeInterface
    // {
    //     return new \DateTime();
    // }




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
