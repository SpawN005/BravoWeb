<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 */
#[ORM\Table(name: 'event')]
#[ORM\Index(name: 'FK_eventcat', columns: ['categorie'])]
#[ORM\UniqueConstraint(name: 'uk_title', columns: ['title'])]
#[ORM\Entity]
class Event
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
    #[ORM\Column(name: 'title', type: 'string', length: 30, nullable: false)]
    private $title;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'description', type: 'string', length: 100, nullable: false)]
    private $description;

    /**
     * @var int
     *
     */
    #[ORM\Column(name: 'nb_placeMax', type: 'integer', nullable: false)]
    private $nbPlacemax;

    // /**
    //  * @var \DateTime
    //  *
    //  */
    // #[ORM\Column(name: 'date_beg', type: 'date', nullable: false)]
    // private $dateBeg;
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateBeg = null;
    // /**
    //  * @var \DateTime
    //  *
    //  */
    // #[ORM\Column(name: 'date_end', type: 'date', nullable: false)]
    // private $dateEnd;
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;
 


    

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'url', type: 'string', length: 255, nullable: false)]
    private $url;

    /**
     * @var \Categorie
     *
     */
    #[ORM\JoinColumn(name: 'categorie', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Categorie')]
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbPlacemax(): ?int
    {
        return $this->nbPlacemax;
    }

    public function setNbPlacemax(int $nbPlacemax): self
    {
        $this->nbPlacemax = $nbPlacemax;

        return $this;
    }

    public function getDateBeg(): ?\DateTimeInterface
    {
        return $this->dateBeg;
    }

    public function setDateBeg(\DateTimeInterface $dateBeg): self
    {
        $this->dateBeg = $dateBeg;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

   

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


}
