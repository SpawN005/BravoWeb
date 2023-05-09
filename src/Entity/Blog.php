<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
#[UniqueEntity(fields: ["title", "description"], message: "This Blog Already Exist.")]

class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("blogs")]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "The Field Title Cannot be empty")]
    #[Assert\Length(max: 20, maxMessage: "The Field Title cannot cannot contain more than {{20}} caracters")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9 ]*$/", message: "The Field Title can only contain letters and numbers")]
    #[Groups("blogs")]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The Field Description Cannot be empty")]
    #[Assert\Length(max: 100, maxMessage: "The Field Desciption cannot cannot contain more than {{20}} caracters")]
    #[Groups("blogs")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Image is required")]
    #[Groups("blogs")]
    private ?string $image = null;

    #[ORM\Column(length: 1000)]
    #[Assert\NotBlank(message: "Content is required")]
    #[Groups("blogs")]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?CategorieBlog $categorie = null;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: CommentaireBlog::class)]
    private Collection $CommentaireBlogs;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: NoteBlog::class)]
    private Collection $NoteBlogs;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?User $author = null;

    public function __construct()
    {
        $this->CommentaireBlogs = new ArrayCollection();
        $this->NoteBlogs = new ArrayCollection();
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
        return $this->CommentaireBlogs;
    }

    public function addCommentaireBlog(CommentaireBlog $CommentaireBlog): self
    {
        if (!$this->CommentaireBlogs->contains($CommentaireBlog)) {
            $this->CommentaireBlogs->add($CommentaireBlog);
            $CommentaireBlog->setBlog($this);
        }

        return $this;
    }

    public function removeCommentaireBlog(CommentaireBlog $CommentaireBlog): self
    {
        if ($this->CommentaireBlogs->removeElement($CommentaireBlog)) {
            // set the owning side to null (unless already changed)
            if ($CommentaireBlog->getBlog() === $this) {
                $CommentaireBlog->setBlog(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NoteBlog>
     */
    public function getNoteBlogs(): Collection
    {
        return $this->NoteBlogs;
    }

    public function addNoteBlog(NoteBlog $NoteBlog): self
    {
        if (!$this->NoteBlogs->contains($NoteBlog)) {
            $this->NoteBlogs->add($NoteBlog);
            $NoteBlog->setBlog($this);
        }

        return $this;
    }

    public function removeNoteBlog(NoteBlog $NoteBlog): self
    {
        if ($this->NoteBlogs->removeElement($NoteBlog)) {
            // set the owning side to null (unless already changed)
            if ($NoteBlog->getBlog() === $this) {
                $NoteBlog->setBlog(null);
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
