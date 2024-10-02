<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
#[Table(name: 'user_employe')]
class Employe extends Personne
{
    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: Cours::class)]
    private Collection $cours;

    public function __construct()
    {
        parent::__construct();
        $this->cours = new ArrayCollection();
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setEmploye($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getEmploye() === $this) {
                $cour->setEmploye(null);
            }
        }

        return $this;
    }
}
