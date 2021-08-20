<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\SlugTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Entity\Traits\UpdateAtTrait;
use App\Entity\Traits\CreatedAtTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="Données invalides")
 * @UniqueEntity(fields={"pseudo"}, message="Données invalides")
 * @ORM\Table(name="user", indexes={@ORM\Index(columns={"pseudo"}, flags={"fulltext"}), @ORM\Index(columns={"slug"})})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;
    use CreatedAtTrait;
    use UpdateAtTrait;
    use SlugTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $pseudo;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $avatar = "default.jpg";

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private bool $isRgpd = true;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private bool $isBanned = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private bool $isVerified = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private bool $isDeleted = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $resetToken = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $downloadToken = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $biography;

    /**
     * @ORM\OneToMany(targetEntity=Recipe::class, mappedBy="user")
     */
    private $recipes;

    /**
     * @ORM\OneToMany(targetEntity=RecipeLike::class, mappedBy="user")
     */
    private $recipeLikes;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->createdAt = new DateTimeImmutable();
        $this->recipes = new ArrayCollection();
        $this->recipeLikes = new ArrayCollection();
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getIsRgpd(): ?bool
    {
        return $this->isRgpd;
    }

    public function setIsRgpd(bool $isRgpd): self
    {
        $this->isRgpd = $isRgpd;

        return $this;
    }

    public function getIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getDownloadToken(): ?string
    {
        return $this->downloadToken;
    }

    public function setDownloadToken(?string $downloadToken): self
    {
        $this->downloadToken = $downloadToken;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setUser($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
            }
        }

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
            $recipeLike->setUser($this);
        }

        return $this;
    }

    public function removeRecipeLike(RecipeLike $recipeLike): self
    {
        if ($this->recipeLikes->removeElement($recipeLike)) {
            // set the owning side to null (unless already changed)
            if ($recipeLike->getUser() === $this) {
                $recipeLike->setUser(null);
            }
        }

        return $this;
    }
}
