<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\SearchEventFormType;


/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Event[] Returns an array of Event objects
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

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findSearch(?DateTimeInterface $dateBeg, ?DateTimeInterface $dateEnd, ?int $nbPlaceMax, ?string $categorie)
{
    $qb = $this->createQueryBuilder('e')
        ->orderBy('e.date_beg', 'ASC');
    
    if ($dateBeg) {
        $qb->andWhere('e.date_beg >= :dateBeg')
           ->setParameter('dateBeg', $dateBeg);
    }
    
    if ($dateEnd) {
        $qb->andWhere('e.date_end <= :dateEnd')
           ->setParameter('dateEnd', $dateEnd);
    }
    
    if ($nbPlaceMax) {
        $qb->andWhere('e.nb_placeMax <= :nbPlaceMax')
           ->setParameter('nbPlaceMax', $nbPlaceMax);
    }
    
    if ($categorie) {
        $qb->andWhere('e.categorie = :categorie')
           ->setParameter('categorie', $categorie);
    }
    
    return $qb->getQuery()->getResult();
}


}
