<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 */
#[ORM\Table(name: 'user')]
#[ORM\UniqueConstraint(name: 'email', columns: ['email'])]
#[ORM\Entity]
class User
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
    #[ORM\Column(name: 'firstName', type: 'string', length: 30, nullable: false)]
    private $firstname;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'lastName', type: 'string', length: 30, nullable: false)]
    private $lastname;

    /**
     * @var int
     *
     */
    #[ORM\Column(name: 'phoneNumber', type: 'integer', nullable: false)]
    private $phonenumber;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'email', type: 'string', length: 30, nullable: false)]
    private $email;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'role', type: 'string', length: 30, nullable: false)]
    private $role;

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'PASSWORD', type: 'string', length: 255, nullable: false)]
    private $password;

    /**
     * @var string|null
     *
     */
    #[ORM\Column(name: 'image', type: 'string', length: 255, nullable: true, options: ['default' => "'aze.png'"])]
    private $image = '\'aze.png\'';

    /**
     * @var string
     *
     */
    #[ORM\Column(name: 'checker', type: 'string', length: 200, nullable: false, options: ['default' => "'usable'"])]
    private $checker = '\'usable\'';
    #[ORM\OneToMany(targetEntity: 'Donater', mappedBy: 'idUser')]

    private $donaters;
    public function __construct()
    {
        $this->donaters = new ArrayCollection();
    }
    public function getDonaters()
    {
        return $this->donaters;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhonenumber(): ?int
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(int $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getChecker(): ?string
    {
        return $this->checker;
    }

    public function setChecker(string $checker): self
    {
        $this->checker = $checker;

        return $this;
    }
    public function __toString()
    {
        return $this->id;
    }
}
