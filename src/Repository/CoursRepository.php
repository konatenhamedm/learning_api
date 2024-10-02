<?php

namespace App\Repository;

use App\Entity\Cours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Cours>
 *
 * @method Cours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cours[]    findAll()
 * @method Cours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursRepository extends ServiceEntityRepository
{
    private $user;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Cours::class);
        $this->user = $security->getUser();
    }

    public function add(Cours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getMatiere($classe)
    {
        return $this->createQueryBuilder('c')
            ->select('distinct(m.id), m.id,m.libelle')
            ->join('c.classe', 'classe')
            ->join('c.matiere', 'm')
            ->join('c.employe', 'employe')
            ->where('classe.id = :classe')
            ->setParameter('classe', $classe)
            ->getQuery()
            ->getResult();
    }
    public function getClasse($annee)
    {
        $sql =  $this->createQueryBuilder('c')
            ->select('distinct(classe.id),classe.id, classe.libelle')
            ->join('c.classe', 'classe')
            ->join('c.matiere', 'm')
            ->andWhere("classe.anneeScolaire = :annee")
            ->setParameter('annee', $annee);

        if ($this->user->getPersonne()->getFonction()->getCode() == 'ENS') {
            $sql->where('employe = :user')
                ->setParameter('user', $this->user->getPersonne());
        }

        return $sql->getQuery()->getResult();
    }

    //    /**
    //     * @return Cours[] Returns an array of Cours objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cours
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
