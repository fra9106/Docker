<?php

declare(strict_types=1);

namespace App\Entity\Traits;

trait NameTrait
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
