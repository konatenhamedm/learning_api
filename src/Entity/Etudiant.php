<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
#[Table(name: 'user_etudiant')]
/* #[Groups(['show_product'])] */
class Etudiant extends Personne
{
  
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_product', 'list_product'])]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_product', 'list_product'])]
    private ?string $adresse = null;



    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_product', 'list_product'])]
    private ?string $boite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_product', 'list_product'])]
    private ?string $fax = null;


    #[ORM\Column(length: 255)]
    #[Groups(['show_product', 'list_product'])]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'etudiants')]
    private ?Nationalite $nationalite = null;

  

    public function getNoms()
    {
        return $this->getNom();
    }


    public function __construct()
    {
       
       
    }


    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }



    public function getBoite(): ?string
    {
        return $this->boite;
    }

    public function setBoite(?string $boite): static
    {
        $this->boite = $boite;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): static
    {
        $this->fax = $fax;

        return $this;
    }


    public function getNationalite(): ?Nationalite
    {
        return $this->nationalite;
    }

    public function setNationalite(?Nationalite $nationalite): static
    {
        $this->nationalite = $nationalite;

        return $this;
    }

}
