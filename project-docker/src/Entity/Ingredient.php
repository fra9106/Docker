<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\SlugTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IsDeletedTrait;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 * @ORM\Table(name="ingredient", indexes={@ORM\Index(columns={"name"}, flags={"fulltext"}), @ORM\Index(columns={"slug"})})
 */
class Ingredient
{
    use IdTrait;
    use SlugTrait;
    use IsDeletedTrait;
    use NameTrait;

    /**
     * @ORM\ManyToMany(targetEntity=Recipe::class, mappedBy="ingredient")
     * @var ArrayCollection<int, Recipe>
     */
    private $recipes;

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
            $recipe->addIngredient($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeIngredient($this);
        }

        return $this;
    }

}
