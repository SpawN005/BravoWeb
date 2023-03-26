<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private $title;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'description', type: 'text', length: 65535, nullable: false)]
    private $description;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'url', type: 'string', length: 200, nullable: false)]
    private $url;

    /**
     * @var \Categorie
     *
     */
    #[ORM\JoinColumn(name: 'categorie', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Categorie')]
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
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


}
