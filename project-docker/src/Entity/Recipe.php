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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $picture;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private bool $isActive = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private bool $isDeleted = false;

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
    private ?string $urlVideo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descriptionVideo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\OneToMany(targetEntity=RecipeLike::class, mappedBy="recipe")
     */
    private $recipeLikes;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->recipeLikes = new ArrayCollection();
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

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
     * @return Collection|RecipeLike[]
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
}
