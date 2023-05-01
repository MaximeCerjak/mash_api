<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;
use App\Entity\Category;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Picture
 *
 * @ORM\Table(name="picture", indexes={@ORM\Index(name="IDX_16DB4F8912469DE2", columns={"category_id"}), @ORM\Index(name="IDX_16DB4F89A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class Picture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"user:pictures", "picture:details"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pic_url", type="string", length=255, nullable=false)
     * @Groups({"user:pictures", "picture:details"})
     */
    private $picUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="pic_legend", type="text", length=0, nullable=false)
     * @Groups({"user:pictures", "picture:details"})
     */
    private $picLegend;

    /**
     * @var string
     *
     * @ORM\Column(name="pic_title", type="string", length=255, nullable=false)
     * @Groups({"user:pictures", "picture:details"})
     */
    private $picTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="pic_format", type="string", length=25, nullable=false)
     * @Groups({"user:pictures", "picture:details"})
     */
    private $picFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="crea_description", type="text", length=0, nullable=true)
     * @Groups({"user:pictures", "picture:details"})
     */
    private $creaDescription;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

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
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="picture")
     */
    private $article = [];


    /**
     * @var Download[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Download", mappedBy="picture")
     */
    private $downloads;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->article = new ArrayCollection();
        $this->downloads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setCreaDescription(string $creaDescription): self
    {
        $this->creaDescription = $creaDescription;

        return $this;
    }

    public function getCategory(): ?object
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function getDownloads(): Collection
    {
        return $this->downloads;
    }

    public function addDownload(Download $download): self
    {
        if (!$this->downloads->contains($download)) {
            $this->downloads[] = $download;
            $download->setPicture($this);
        }

        return $this;
    }
}
