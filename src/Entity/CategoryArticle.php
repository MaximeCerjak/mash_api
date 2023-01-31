<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * CategoryArticle
 *
 * @ORM\Table(name="category_article")
 * @ORM\Entity
 */
class CategoryArticle
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
     * @var string
     *
     * @ORM\Column(name="cat_name", type="string", length=125, nullable=false)
     */
    private $catName;

    /**
     * @var string
     *
     * @ORM\Column(name="cat_description", type="text", length=0, nullable=false)
     */
    private $catDescription;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Article", mappedBy="categoryArticle")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="article_id", referencedColumnName="category_article_id")
     * })
     */
    private $article;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->article = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setCatDescription(string $catDescription): self
    {
        $this->catDescription = $catDescription;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticle(): \Doctrine\Common\Collections\Collection
    {
        return $this->article;
    }

}
