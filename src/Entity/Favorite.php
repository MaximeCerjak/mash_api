<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Favorite
 *
 * @ORM\Table(name="favorite", indexes={@ORM\Index(name="IDX_68C58ED9A76ED395", columns={"user_id"}), @ORM\Index(name="IDX_68C58ED9EE45BDBF", columns={"picture_id"})})
 * @ORM\Entity
 */
class Favorite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="favorite_date", type="datetime", nullable=false)
     */
    private $favoriteDate = 'current_timestamp()';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Picture
     *
     * @ORM\ManyToOne(targetEntity="Picture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     * })
     */
    private $picture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFavoriteDate(): ?\DateTimeInterface
    {
        return $this->favoriteDate;
    }

    public function setFavoriteDate(\DateTimeInterface $favoriteDate): self
    {
        $this->favoriteDate = $favoriteDate;

        return $this;
    }

    public function getUser(): ?object
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPicture(): ?object
    {
        return $this->picture;
    }

    public function setPicture(?Picture $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

}
