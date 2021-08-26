<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\SlugTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IsDeletedTrait;
use App\Repository\KeywordRepository;

/**
 * @ORM\Entity(repositoryClass=KeywordRepository::class)
 * @ORM\Table(name="keyword", indexes={@ORM\Index(columns={"name"}, flags={"fulltext"}), @ORM\Index(columns={"slug"})})
 */
class Keyword
{
    use IdTrait;
    use SlugTrait;
    use IsDeletedTrait;
    use NameTrait;

    /**
     * @ORM\ManyToMany(targetEntity=Recipe::class, mappedBy="keyword")
     * @var ArrayCollection<int, Recipe>
     */
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
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
            $recipe->addKeyword($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeKeyword($this);
        }

        return $this;
    }
}
