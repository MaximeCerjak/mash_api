<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $userName = null;

    #[ORM\Column(length: 150)]
    private ?string $userPseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $userEmail = null;

    #[ORM\Column(length: 255)]
    private ?string $userPassword = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $userStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getuser_id(): ?int
    {
        return $this->user_id;
    }

    public function setuser_id(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserPseudo(): ?string
    {
        return $this->userPseudo;
    }

    public function setUserPseudo(string $userPseudo): self
    {
        $this->userPseudo = $userPseudo;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->userPassword;
    }

    public function setUserPassword(string $userPassword): self
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    public function getUserStatus(): ?int
    {
        return $this->userStatus;
    }

    public function setUserStatus(int $userStatus): self
    {
        $this->userStatus = $userStatus;

        return $this;
    }
}
