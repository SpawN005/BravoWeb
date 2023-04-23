<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Noteoeuvre
 *
 */
#[ORM\Table(name: 'noteoeuvre')]
#[ORM\Index(name: 'fk_user', columns: ['id_user'])]
#[ORM\Index(name: 'IDX_DAD661B513C99B13', columns: ['id_oeuvre'])]
#[ORM\UniqueConstraint(name: 'idx_noteoeuvre_id', columns: ['id_oeuvre', 'id_user'])]
#[ORM\Entity]
class Noteoeuvre
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
     * @var int
     *
     */
    #[ORM\Column(name: 'note', type: 'integer', nullable: false)]
    private $note;

    /**
     * @var \Artwork
     *
     */
    #[ORM\JoinColumn(name: 'id_oeuvre', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Artwork')]
    private $idOeuvre;

    /**
     * @var \User
     *
     */
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getIdOeuvre(): ?Artwork
    {
        return $this->idOeuvre;
    }

    public function setIdOeuvre(?Artwork $idOeuvre): self
    {
        $this->idOeuvre = $idOeuvre;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }
}
