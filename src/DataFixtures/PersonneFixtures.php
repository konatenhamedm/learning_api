<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;;

class PersonneFixtures extends Fixture implements DependentFixtureInterface
{
    public const DEFAULT_PERSONNE_REFERENCE = 'default-personne';
    public function load(ObjectManager $manager): void
    {
        $personne = new Personne();
        $personne->setNom('UFR');
        $personne->setPrenom('SEG');
        $personne->setCivilite($this->getReference(CiviliteFixtures::DEFAULT_CIVILITE_REFERENCE));
        $personne->setFonction($this->getReference(FonctionFixtures::DEFAULT_FONCTION_REFERENCE));
        $personne->setGenre($this->getReference(GenreFixtures::DEFAULT_GENRE_REFERENCE));
        $personne->setEmail('admin@gmail.com');
        $personne->setContact('');
        $personne->setDateNaissance(new \DateTime());
        $personne->setLieuNaissance('Abidjan');
        // $product = new Product();
        $manager->persist($personne);

        $manager->flush();

        $this->addReference(self::DEFAULT_PERSONNE_REFERENCE, $personne);
    }


    public function getDependencies()
    {
        return [
            CiviliteFixtures::class,
            GenreFixtures::class,
            FonctionFixtures::class
        ];
    }
}
