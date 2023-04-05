<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Picture;
use App\Entity\User;

/**
 * Download
 *
 * @ORM\Table(name="download", indexes={@ORM\Index(name="IDX_781A8270A76ED395", columns={"user_id"}), @ORM\Index(name="IDX_781A8270EE45BDBF", columns={"picture_id"})})
 * @ORM\Entity
 */
class Download
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
     * @ORM\Column(name="download_date", type="datetime", nullable=false)
     */
    private $downloadDate = 'current_timestamp()';

    /**
     * @var \Picture
     *
     * @ORM\ManyToOne(targetEntity="Picture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     * })
     */
    private $picture;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDownloadDate(): ?\DateTimeInterface
    {
        return $this->downloadDate;
    }

    public function setDownloadDate(\DateTimeInterface $downloadDate): self
    {
        $this->downloadDate = $downloadDate;

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

    public function getUser(): ?object
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
