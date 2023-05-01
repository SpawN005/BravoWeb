<?php

namespace App\Entity;

use App\Repository\CategorieDonationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieDonationRepository::class)]
#[ORM\UniqueConstraint(name: 'NomCategorie', columns: ['NomCategorie'])]
class CategorieDonation
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
    #[Assert\NotBlank(message: 'Please')]
    private $nomcategorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(string $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }
    public function __toString()
    {
        return $this->nomcategorie;
    }
}
