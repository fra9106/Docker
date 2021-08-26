<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\CreatedAtTrait;
use App\Repository\RecipeLikeRepository;

/**
 * @ORM\Entity(repositoryClass=RecipeLikeRepository::class)
 */
class RecipeLike
{
    use IdTrait;
    use CreatedAtTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="recipeLikes")
     */
    private ?Recipe $recipe;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipeLikes")
     */
    private ?User $user;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

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
}
