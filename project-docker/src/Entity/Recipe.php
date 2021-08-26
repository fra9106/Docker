<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\SlugTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\UpdateAtTrait;
use App\Repository\RecipeRepository;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IsDeletedTrait;
use App\Entity\Traits\NameTrait;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @ORM\Table(name="recipe", indexes={@ORM\Index(columns={"name","content"}, flags={"fulltext"}), @ORM\Index(columns={"slug"})})
 */
class Recipe
{
    use IdTrait;
    use CreatedAtTrait;
    use UpdateAtTrait;
    use SlugTrait;
    use NameTrait;
    use IsDeletedTrait;
    

    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $picture;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private bool $isActive = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private bool $isSubmited = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private bool $isFromYoutubeApi = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $urlVideo = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descriptionVideo = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\OneToMany(targetEntity=RecipeLike::class, mappedBy="recipe")
     * @var ArrayCollection<int, RecipeLike>
     */
    private $recipeLikes;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="recipe")
     * @var ArrayCollection<int, Comment>
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Region $region;

    /**
     * @ORM\ManyToMany(targetEntity=Keyword::class, inversedBy="recipes")
     * @var ArrayCollection<int, Keyword>
     */
    private $keyword;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="recipes")
     * @var ArrayCollection<int, Category>
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Ingredient::class, inversedBy="recipes")
     * @var ArrayCollection<int, Ingredient>
     */
    private $ingredient;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->recipeLikes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->keyword = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->ingredient = new ArrayCollection();
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsSubmited(): ?bool
    {
        return $this->isSubmited;
    }

    public function setIsSubmited(bool $isSubmited): self
    {
        $this->isSubmited = $isSubmited;

        return $this;
    }

    public function getIsFromYoutubeApi(): ?bool
    {
        return $this->isFromYoutubeApi;
    }

    public function setIsFromYoutubeApi(bool $isFromYoutubeApi): self
    {
        $this->isFromYoutubeApi = $isFromYoutubeApi;

        return $this;
    }

    public function getUrlVideo(): ?string
    {
        return $this->urlVideo;
    }

    public function setUrlVideo(?string $urlVideo): self
    {
        $this->urlVideo = $urlVideo;

        return $this;
    }

    public function getDescriptionVideo(): ?string
    {
        return $this->descriptionVideo;
    }

    public function setDescriptionVideo(?string $descriptionVideo): self
    {
        $this->descriptionVideo = $descriptionVideo;

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

    /**
     * @return ArrayCollection<int, RecipeLike>
     */
    public function getRecipeLikes(): Collection
    {
        return $this->recipeLikes;
    }

    public function addRecipeLike(RecipeLike $recipeLike): self
    {
        if (!$this->recipeLikes->contains($recipeLike)) {
            $this->recipeLikes[] = $recipeLike;
            $recipeLike->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeLike(RecipeLike $recipeLike): self
    {
        if ($this->recipeLikes->removeElement($recipeLike)) {
            // set the owning side to null (unless already changed)
            if ($recipeLike->getRecipe() === $this) {
                $recipeLike->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category); 

        return $this;
    }

    /**
     * @return ArrayCollection<int, Keyword>
     */
    public function getKeyword(): Collection
    {
        return $this->keyword;
    }

    public function addKeyword(Keyword $keyword): self
    {
        if (!$this->keyword->contains($keyword)) {
            $this->keyword[] = $keyword;
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): self
    {
        $this->keyword->removeElement($keyword);

        return $this;
    }

    /**
     * @return ArrayCollection<int, Ingredient>
     */
    public function getIngredient(): Collection
    {
        return $this->ingredient;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredient->contains($ingredient)) {
            $this->ingredient[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredient->removeElement($ingredient); 

        return $this;
    }
}
