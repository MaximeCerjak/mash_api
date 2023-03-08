<?php

namespace App\Entity;

use App\Repository\CategoryArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryArticleRepository::class)]
class CategorieArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity:"Article::class", mappedBy:"articles")]
    #[ORM\JoinColumn(name:"article_id", referencedColumnName:"id")]
    private $article;

    #[ORM\Column(length: 100)]
    private ?string $catName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $catDescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): self
    {
        $this->article = $article;
        return $this;
    }

    public function getCatName(): ?string
    {
        return $this->catName;
    }

    public function setCatName(string $catName): self
    {
        $this->catName = $catName;

        return $this;
    }

    public function getCatDescription(): ?string
    {
        return $this->catDescription;
    }

    public function setCatDescription(?string $catDescription): self
    {
        $this->catDescription = $catDescription;

        return $this;
    }
}
