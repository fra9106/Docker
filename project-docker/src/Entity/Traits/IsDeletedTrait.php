<?php

declare(strict_types=1);

namespace App\Entity\Traits;

trait IsDeletedTrait
{
     /**
      * @ORM\Column(type="boolean", options={"default" : 0})
      */
     private bool $isDeleted = false;

     public function getIsDeleted(): ?bool
     {
          return $this->isDeleted;
     }

     public function setIsDeleted(bool $isDeleted): self
     {
          $this->isDeleted = $isDeleted;

          return $this;
     }
}
