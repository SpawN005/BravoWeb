<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Blog
 *
 */
#[ORM\Table(name: 'blog')]
#[ORM\Index(name: 'FK_blogcat', columns: ['categorie'])]
#[ORM\Index(name: 'fk_user', columns: ['author'])]
#[ORM\Entity]
class Blog
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
     * @var string
     *
     */
    #[ORM\Column(name: 'url', type: 'string', length: 10000, nullable: false)]
    private $url;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'content', type: 'string', length: 1000, nullable: false)]
    private $content;

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
    #[ORM\JoinColumn(name: 'author', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private $author;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }


}
