<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Typereclamation
 *
 */
#[ORM\Table(name: 'typereclamation')]
#[ORM\Entity]
class Typereclamation
{
    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'typeReclamation', type: 'string', length: 30, nullable: false)]
    private $typereclamation;

    /**
     * @var \Reclamation
     *
     */
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\OneToOne(targetEntity: 'Reclamation')]
    private $id;

    public function getTypereclamation(): ?string
    {
        return $this->typereclamation;
    }

    public function setTypereclamation(string $typereclamation): self
    {
        $this->typereclamation = $typereclamation;

        return $this;
    }

    public function getId(): ?Reclamation
    {
        return $this->id;
    }

    public function setId(?Reclamation $id): self
    {
        $this->id = $id;

        return $this;
    }


}
