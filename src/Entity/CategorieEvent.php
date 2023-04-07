<?php

namespace App\Entity;

use App\Repository\CategorieEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieEventRepository::class)]
class CategorieEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCategorieEvent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorieEvent(): ?string
    {
        return $this->nomCategorieEvent;
    }

    public function setNomCategorieEvent(string $nomCategorieEvent): self
    {
        $this->nomCategorieEvent = $nomCategorieEvent;

        return $this;
    }
}
