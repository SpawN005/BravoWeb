<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?CategorieBlog $categorie = null;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: CommentaireBlog::class)]
    private Collection $commentaireBlogs;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: NoteBlog::class)]
    private Collection $noteBlogs;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?User $author = null;

    public function __construct()
    {
        $this->commentaireBlogs = new ArrayCollection();
        $this->noteBlogs = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function getCategorie(): ?CategorieBlog
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieBlog $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, CommentaireBlog>
     */
    public function getCommentaireBlogs(): Collection
    {
        return $this->commentaireBlogs;
    }

    public function addCommentaireBlog(CommentaireBlog $commentaireBlog): self
    {
        if (!$this->commentaireBlogs->contains($commentaireBlog)) {
            $this->commentaireBlogs->add($commentaireBlog);
            $commentaireBlog->setBlog($this);
        }

        return $this;
    }

    public function removeCommentaireBlog(CommentaireBlog $commentaireBlog): self
    {
        if ($this->commentaireBlogs->removeElement($commentaireBlog)) {
            // set the owning side to null (unless already changed)
            if ($commentaireBlog->getBlog() === $this) {
                $commentaireBlog->setBlog(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NoteBlog>
     */
    public function getNoteBlogs(): Collection
    {
        return $this->noteBlogs;
    }

    public function addNoteBlog(NoteBlog $noteBlog): self
    {
        if (!$this->noteBlogs->contains($noteBlog)) {
            $this->noteBlogs->add($noteBlog);
            $noteBlog->setBlog($this);
        }

        return $this;
    }

    public function removeNoteBlog(NoteBlog $noteBlog): self
    {
        if ($this->noteBlogs->removeElement($noteBlog)) {
            // set the owning side to null (unless already changed)
            if ($noteBlog->getBlog() === $this) {
                $noteBlog->setBlog(null);
            }
        }

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
