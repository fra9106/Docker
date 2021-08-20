<?php 

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;

trait UpdateAtTrait 
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $updatedAt = null;

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}