<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Noteblog
 *
 */
#[ORM\Table(name: 'noteblog')]
#[ORM\Index(name: 'id_blog', columns: ['id_blog'])]
#[ORM\Entity]
class Noteblog
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

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

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
