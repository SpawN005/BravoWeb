<?php

namespace App\Entity;

use App\Repository\CategorieBlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategorieBlogRepository::class)]
class CategorieBlog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Categorie is required")]
    private ?string $nomCategorie = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Blog::class)]
    private Collection $blogs;

    public function __construct()
    {
        $this->blogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setCategorie($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getCategorie() === $this) {
                $blog->setCategorie(null);
            }
        }

        return $this;
    }
}
