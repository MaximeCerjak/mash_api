<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity:"User::class", inversedBy:"articles")]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    private $user;

    // #[ORM\Column]
    // #[ORM\ManyToOne(targetEntity: "User::class")]
    // private ?int $user_id = null;

    #[ORM\Column(length: 255)]
    private ?string $articleTitle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $articleContent = null;

    #[ORM\ManyToOne(targetEntity:"CategoryArticle::class", inversedBy:"articles")]
    #[ORM\JoinColumn(name:"catArt_id", referencedColumnName:"catArt_id")]
    private $catArt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setuser(int $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticleTitle(): ?string
    {
        return $this->articleTitle;
    }

    public function setArticleTitle(string $articleTitle): self
    {
        $this->articleTitle = $articleTitle;

        return $this;
    }

    public function getArticleContent(): ?string
    {
        return $this->articleContent;
    }

    public function setArticleContent(string $articleContent): self
    {
        $this->articleContent = $articleContent;

        return $this;
    }

    public function getcatArt_id(): ?int
    {
        return $this->catArt;
    }

    public function setcatArt_id(int $catArt): self
    {
        $this->catArt = $catArt;

        return $this;
    }

}
