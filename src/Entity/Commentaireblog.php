<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaireblog
 *
 */
#[ORM\Table(name: 'commentaireblog')]
#[ORM\Index(name: 'fk_CommBlog', columns: ['id_blog'])]
#[ORM\Index(name: 'fk_CommUser', columns: ['id_user'])]
#[ORM\Entity]
class Commentaireblog
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
    #[ORM\Column(name: 'content', type: 'string', length: 1000, nullable: false)]
    private $content;

    /**
     * @var \User
     *
     */
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private $idUser;

    /**
     * @var \Blog
     *
     */
    #[ORM\JoinColumn(name: 'id_blog', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Blog')]
    private $idBlog;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdBlog(): ?Blog
    {
        return $this->idBlog;
    }

    public function setIdBlog(?Blog $idBlog): self
    {
        $this->idBlog = $idBlog;

        return $this;
    }


}
