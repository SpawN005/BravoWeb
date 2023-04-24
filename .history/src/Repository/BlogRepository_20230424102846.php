<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function save(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Blog[] Returns an array of Blog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Blog
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function findByTitleAndCategorie($title, $categorie)
{
    $qb = $this->createQueryBuilder('b')
        ->where('b.title LIKE :title')
        ->setParameter('title', '%'.$title.'%');
    
    if ($categorie) {
        $qb->andWhere('b.categorie = :categorie')
            ->setParameter('categorie', $categorie);
    }
    
    return $qb->getQuery()
        ->getResult();
}



   /* public function findSearch( ?string $title, ?string $categorie)
    {
        $qb = $this->createQueryBuilder('b');
        
        
        
        
        if ($title) {
            $qb->andWhere('b.title <= :title')
            ->setParameter('title', $title);
        }
        
        if ($categorie) {
            $qb->andWhere('b.categorie = :categorie')
            ->setParameter('categorie', $categorie);
        }
        
    return $qb->getQuery()->getResult();
}*/


    public function countByNote(): ?float
    {
        $qb = $this->createQueryBuilder('n')
                ->select('AVG(n.note) as average')
                ->getQuery();

        return $qb->getSingleScalarResult();
    }*/

}