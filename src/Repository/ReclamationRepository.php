<?php

namespace App\Repository;

use App\Entity\Typereclamation;
use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//   

public function findByTitleAndStateAndCreationDate($title, $etat, $dateCreation)
{
    $qb = $this->createQueryBuilder('r')
        ->where('r.title LIKE :title')
        ->setParameter('title', '%'.$title.'%');
    
    if ($etat) {
        $qb->andWhere('r.etat = :etat')
            ->setParameter('etat', $etat);
    }
    
    if ($dateCreation) {
        $qb->andWhere('r.dateCreation = :dateCreation')
        ->setParameter('dateCreation', $dateCreation);
    }
    
    return $qb->getQuery()
        ->getResult();
}

public function countByEtat()
{
    $qb = $this->createQueryBuilder('r')
        ->select('r.etat, COUNT(r.id) as total')
        ->groupBy('r.etat');

    return $qb->getQuery()->getResult();
}





}