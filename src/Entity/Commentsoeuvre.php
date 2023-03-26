<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commentsoeuvre
 *
 */
#[ORM\Table(name: 'commentsoeuvre')]
#[ORM\Index(name: 'oeuvre_id', columns: ['oeuvre_id'])]
#[ORM\Index(name: 'user_id', columns: ['user_id'])]
#[ORM\Entity]
class Commentsoeuvre
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
    #[ORM\Column(name: 'comment', type: 'string', length: 255, nullable: false)]
    private $comment;

    /**
     * @var \DateTime
     *
     */
    #[ORM\Column(name: 'timestamp', type: 'datetime', nullable: false, options: ['default' => 'current_timestamp()'])]
    private $timestamp = 'current_timestamp()';

    /**
     * @var \Artwork
     *
     */
    #[ORM\JoinColumn(name: 'oeuvre_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Artwork')]
    private $oeuvre;

    /**
     * @var \User
     *
     */
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getOeuvre(): ?Artwork
    {
        return $this->oeuvre;
    }

    public function setOeuvre(?Artwork $oeuvre): self
    {
        $this->oeuvre = $oeuvre;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
