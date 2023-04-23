<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Artwork
 *
 */
#[ORM\Table(name: 'artwork')]
#[ORM\Index(name: 'artwork_fk', columns: ['owner'])]
#[ORM\Index(name: 'FK_artwork', columns: ['categorie'])]
#[ORM\UniqueConstraint(name: 'title', columns: ['title'])]
#[ORM\Entity]
class Artwork
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
    #[Assert\NotBlank(message: "Title is required")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Title must be at least {{ limit }} characters long",
        maxMessage: "Title cannot be longer than {{ limit }} characters"
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z][a-zA-Z0-9\s]*$/",
        message: "Title should not start with a space, number or special character",
    )]
    private $title;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'description', type: 'text', length: 65535, nullable: false)]
    #[Assert\NotBlank(message: "Description is required")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z][a-zA-Z0-9\s]*$/",
        message: "description should not start with a space, number or special character",
    )]



    private $description;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'url', type: 'string', length: 200, nullable: false)]
    #[Assert\NotBlank(message: "Image is required")]
    private $url;

    /**
     * @var \Categorie
     *
     */
    #[ORM\JoinColumn(name: 'categorie', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Categorie')]
    #[Assert\NotBlank(message: "Categorie is required")]
    private $categorie;

    /**
     * @var \User
     *
     */
    #[ORM\JoinColumn(name: 'owner', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getTitle(); // assuming the Artwork entity has a "title" property
    }
}
