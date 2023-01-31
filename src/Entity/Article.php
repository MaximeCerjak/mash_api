<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="IDX_23A0E66548AD6E2", columns={"category_article_id"}), @ORM\Index(name="IDX_23A0E66A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class Article
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
     * @ORM\Column(name="article_title", type="string", length=255, nullable=false)
     */
    private $articleTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="article_content", type="text", length=0, nullable=false)
     */
    private $articleContent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="article_date", type="datetime", nullable=false)
     */
    private $articleDate;

    /**
     * @var \CategoryArticle
     *
     * @ORM\ManyToOne(targetEntity="CategoryArticle", inversedBy="article")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_article_id", referencedColumnName="id")
     * })
     */
    private $categoryArticle;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Picture", inversedBy="article")
     * @ORM\JoinTable(name="article_picture",
     *   joinColumns={
     *     @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     *   }
     * )
     */
    private $picture = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getArticleDate(): ?\DateTimeInterface
    {
        return $this->articleDate;
    }

    public function setArticleDate(\DateTimeInterface $articleDate): self
    {
        $this->articleDate = $articleDate;

        return $this;
    }

    public function getCategoryArticle(): ?object
    {
        return $this->categoryArticle;
    }

    public function setCategoryArticle(?CategoryArticle $categoryArticle): self
    {
        $this->categoryArticle = $categoryArticle;

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

    /**
     * @return Collection|Picture[]
     */
    public function getPicture(): Collection
    {
        return $this->picture;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->picture->contains($picture)) {
            $this->picture[] = $picture;
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        $this->picture->removeElement($picture);

        return $this;
    }
}
