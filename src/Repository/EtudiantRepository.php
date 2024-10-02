<?php

namespace App\Repository;

use App\Entity\Deliberation;
use App\Entity\Employe;
use App\Entity\Etudiant;
use App\Entity\Examen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etudiant>
 *
 * @method Etudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiant[]    findAll()
 * @method Etudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function add(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    /**
     * @return mixed
     */
    public function withoutDeliberation(Examen $examen)
    {
        $qb = $this->createQueryBuilder('e');
        $qbExists = $this->getEntityManager()->createQueryBuilder('a');
        $stmtExists = $qbExists->select('d')->from(Deliberation::class, 'd')
            ->andWhere('d.etudiant = e.id')
            ->andWhere('d.examen = :examen');
        $qb->select('e')
            ->andWhere($qb->expr()->not($qb->expr()->exists($stmtExists->getDQL())))
            ->setParameter('examen', $examen);

        return $qb;
    }

//    /**
//     * @return Etudiant[] Returns an array of Etudiant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Etudiant
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
