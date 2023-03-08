<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\ManyToOne(targetEntity:"User::class", inversedBy:"articles")]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"user_id")]
    private $user;

    #[ORM\Column(length: 255)]
    private ?string $picUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $picLegend = null; 

    #[ORM\Column(length: 255)]
    private ?string $picTitle = null;

    #[ORM\Column(length: 20)]
    private ?string $picFormat = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $creaDescription = null;

    #[ORM\Column]
    private ?int $cat_id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $mediaType = null;

    #[ORM\Column(nullable: true)]
    private ?int $article_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPicUrl(): ?string
    {
        return $this->picUrl;
    }

    public function setPicUrl(string $picUrl): self
    {
        $this->picUrl = $picUrl;

        return $this;
    }

    public function getPicLegend(): ?string
    {
        return $this->picLegend;
    }

    public function setPicLegend(string $picLegend): self
    {
        $this->picLegend = $picLegend;

        return $this;
    }

    public function getPicTitle(): ?string
    {
        return $this->picTitle;
    }

    public function setPicTitle(string $picTitle): self
    {
        $this->picTitle = $picTitle;

        return $this;
    }

    public function getPicFormat(): ?string
    {
        return $this->picFormat;
    }

    public function setPicFormat(string $picFormat): self
    {
        $this->picFormat = $picFormat;

        return $this;
    }

    public function getCreaDescription(): ?string
    {
        return $this->creaDescription;
    }

    public function setCreaDescription(?string $creaDescription): self
    {
        $this->creaDescription = $creaDescription;

        return $this;
    }

    public function getCatId(): ?int
    {
        return $this->cat_id;
    }

    public function setCatId(int $cat_id): self
    {
        $this->cat_id = $cat_id;

        return $this;
    }

    public function getMediaType(): ?int
    {
        return $this->mediaType;
    }

    public function setMediaType(int $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getArticleId(): ?int
    {
        return $this->article_id;
    }

    public function setArticleId(?int $article_id): self
    {
        $this->article_id = $article_id;

        return $this;
    }
}
