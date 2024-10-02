<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class GroupeFixtures extends Fixture
{
    public const DEFAULT_GROUP_REFERENCE = 'admin-group';
    public function load(ObjectManager $manager): void
    {
        $groupAdmin = new Groupe();
        $groupAdmin->setLibelle('Super Administrateur');
        $groupAdmin->setDescription('Super Administrateur');
        $groupAdmin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']);
        $manager->persist($groupAdmin);
        $manager->flush();

        $this->addReference(self::DEFAULT_GROUP_REFERENCE, $groupAdmin);
    }
}
