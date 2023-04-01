<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function findAllCategories()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.nomcategorie', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findCategorieById($id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function addCategorie(Categorie $categorie)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($categorie);
        $entityManager->flush();
    }

    public function updateCategorie(Categorie $categorie)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    public function deleteCategorie(Categorie $categorie)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($categorie);
        $entityManager->flush();
    }
}
