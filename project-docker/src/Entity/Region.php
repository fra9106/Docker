<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\SlugTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\UpdateAtTrait;
use App\Repository\RegionRepository;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IsDeletedTrait;
use App\Entity\Traits\NameTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @ORM\Table(name="region", indexes={@ORM\Index(columns={"name","description"}, flags={"fulltext"}), @ORM\Index(columns={"slug"})})
 */
class Region
{
    use IdTrait;
    use CreatedAtTrait;
    use UpdateAtTrait;
    use SlugTrait;
    use IsDeletedTrait;
    use NameTrait;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $picture;

    /**
     * @ORM\OneToMany(targetEntity=Recipe::class, mappedBy="region")
     * @var ArrayCollection<int, Recipe>
     */
    private $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return ArrayCollection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setRegion($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getRegion() === $this) {
                $recipe->setRegion(null);
            }
        }

        return $this;
    }
}
