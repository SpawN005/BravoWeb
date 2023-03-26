<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Donater
 *
 */
#[ORM\Table(name: 'donater')]
#[ORM\Index(name: 'fk_user', columns: ['id_user'])]
#[ORM\Index(name: 'IDX_4BD61C33613E6D35', columns: ['id_donation'])]
#[ORM\UniqueConstraint(name: 'idx_donation', columns: ['id_donation', 'id_user'])]
#[ORM\Entity]
class Donater
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
    #[ORM\Column(name: 'amount', type: 'integer', nullable: false)]
    private $amount;

    /**
     * @var \Donation
     *
     */
    #[ORM\JoinColumn(name: 'id_donation', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Donation')]
    private $idDonation;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getIdDonation(): ?Donation
    {
        return $this->idDonation;
    }

    public function setIdDonation(?Donation $idDonation): self
    {
        $this->idDonation = $idDonation;

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
