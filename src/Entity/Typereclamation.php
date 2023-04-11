<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Typereclamation
 *
 */
#[ORM\Table(name: 'typereclamation')]
#[ORM\Entity]
#[UniqueEntity(fields:["typereclamation"], message:"Ce type existe déjà.")]

class Typereclamation
{
   
    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'typeReclamation', type: 'string', length: 30, nullable: false)]
    #[Assert\NotBlank(message:"Le champ type ne peut pas être vide")]
    #[Assert\Length(max:10, maxMessage:"Le champ type ne peut pas contenir plus de {{ 10 }} caractères")]
    #[Assert\Regex(pattern:"/^[a-zA-Z0-9 ]*$/", message:"Le champ type ne peut contenir que des lettres, des chiffres et des espaces")]
    private $typereclamation;

    /**
     * @var \Reclamation
     *
     */

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]


    private $id;

    #[ORM\OneToMany(mappedBy: 'typereclamation', targetEntity: Reclamation::class)]
    private $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

    public function getTypereclamation(): ?string
    {
        return $this->typereclamation;
    }

    public function setTypereclamation(string $typereclamation): self
    {
        $this->typereclamation = $typereclamation;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    //public function getId(): ?Reclamation
    //{
     //   return $this->id;
    //}

    //public function setId(?Reclamation $id): self
    //{
       // $this->id = $id;

        //return $this;
    //}

    public function __toString(): string
{
    return $this->typereclamation ?? '';
}




}
