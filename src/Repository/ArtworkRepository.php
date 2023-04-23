<?php

namespace App\Repository;

use App\Entity\Artwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieBlog>
 *
 * @method CategorieBlog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieBlog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieBlog[]    findAll()
 * @method CategorieBlog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artwork::class);
    }

    public function save(Artwork $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Artwork $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeByCategoryId($categoryID): void
    {
        $entities = $this->createQueryBuilder('o')
            ->leftJoin('o.categorie', 'c')
            ->andWhere('c.id = :categorie')
            ->setParameter('categorie', $categoryID)
            ->getQuery()
            ->getResult();

        foreach ($entities as $entity) {
            $this->_em->remove($entity);
        }

        $this->_em->flush();
    }
}

    //    /**
    //     * @return CategorieBlog[] Returns an array of CategorieBlog objects
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

    //    public function findOneBySomeField($value): ?CategorieBlog
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
