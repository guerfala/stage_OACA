<?php

namespace App\Repository;

use App\Entity\Interventions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Interventions>
 *
 * @method Interventions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interventions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interventions[]    findAll()
 * @method Interventions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interventions::class);
    }

    public function save(Interventions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Interventions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchBynom($value)
    {
        return $this->createQueryBuilder('e')
            ->where('e.au_service LIKE :value OR e.service_demandeur LIKE :value OR e.reference LIKE :value OR e.etat LIKE :value  ')
            ->setParameter('value', '%'.$value.'%')
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Interventions[] Returns an array of Interventions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Interventions
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
