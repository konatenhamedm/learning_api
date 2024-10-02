<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;;

class UtilisateurFixtures extends Fixture implements DependentFixtureInterface
{
    public const DEFAULT_USER_REFERENCE = 'default-user';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $utilisateur = new Utilisateur();
        $utilisateur->addGroupe($this->getReference(GroupeFixtures::DEFAULT_GROUP_REFERENCE));
        /* $utilisateur->setUsername('admin_ufr'); */
        $utilisateur->setEmail('admin@gmail.com');
        $utilisateur->setPassword($this->hasher->hashPassword($utilisateur, 'admin_ufr'));
        $utilisateur->setPersonne($this->getReference(PersonneFixtures::DEFAULT_PERSONNE_REFERENCE));
        // $product = new Product();
        // $manager->persist($product);
        $manager->persist($utilisateur);

        $manager->flush();

        $this->addReference(self::DEFAULT_USER_REFERENCE, $utilisateur);
    }


    public function getDependencies()
    {
        return [
            PersonneFixtures::class,
            GroupeFixtures::class,
        ];
    }
}
