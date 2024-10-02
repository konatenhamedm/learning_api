<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[Table(name: 'user_groupe')]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: UtilisateurGroupe::class)]
    private Collection $utilisateurGroupes;

    public function __construct()
    {
        $this->utilisateurGroupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, UtilisateurGroupe>
     */
    public function getUtilisateurGroupes(): Collection
    {
        return $this->utilisateurGroupes;
    }

    public function addUtilisateurGroupe(UtilisateurGroupe $utilisateurGroupe): static
    {
        if (!$this->utilisateurGroupes->contains($utilisateurGroupe)) {
            $this->utilisateurGroupes->add($utilisateurGroupe);
            $utilisateurGroupe->setGroupe($this);
        }

        return $this;
    }

    public function removeUtilisateurGroupe(UtilisateurGroupe $utilisateurGroupe): static
    {
        if ($this->utilisateurGroupes->removeElement($utilisateurGroupe)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurGroupe->getGroupe() === $this) {
                $utilisateurGroupe->setGroupe(null);
            }
        }

        return $this;
    }
}
