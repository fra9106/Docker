<?php 

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTimeImmutable;

trait CreatedAtTrait 
{
    /**
     * @ORM\Column(type="datetime_immutable", options={"default" : "CURRENT_TIMESTAMP"})
     */
    private DateTimeImmutable $createdAt;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}