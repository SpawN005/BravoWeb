<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Categorie
 *
 */
#[ORM\Table(name: 'categorie')]
#[ORM\UniqueConstraint(name: 'NomCategorie', columns: ['NomCategorie'])]
#[ORM\Entity]
class Categorie
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
    #[ORM\Column(name: 'NomCategorie', type: 'string', length: 100, nullable: false)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9_-]+$/",
        message: "Categorie should not start with a space, number or special character",
    )]
    #[Assert\NotBlank(message: "Categorie is required")]
    #[Assert\Length(
        min: 3,
        max: 10,
        minMessage: "Categorie must be at least {{ limit }} characters long",
        maxMessage: "Categorie cannot be longer than {{ limit }} characters"
    )]
    private $nomcategorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(?string $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }
    public function __toString()
    {
        return $this->nomcategorie;
    }
}
