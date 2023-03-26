<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 */
#[ORM\Table(name: 'role')]
#[ORM\Index(name: 'user_id', columns: ['user_id'])]
#[ORM\Entity]
class Role
{
    /**
     * @var int
     *
     */
    #[ORM\Column(name: 'role_ID', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $roleId;

    /**
     * @var string|null
     *
     */
    #[ORM\Column(name: 'role', type: 'string', length: 20, nullable: true, options: ['default' => "guest"])]
    private $role = 'NULL';

    /**
     * @var \User
     *
     */
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'User')]
    private $user;

    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

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
