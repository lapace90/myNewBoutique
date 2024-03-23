<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\SearchFilters;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

   /**
    * @return Product[] Returns an array of Product objects
    */
    public function findByFilters(SearchFilters $searchFilters)
        {
        $qb = $this->createQueryBuilder('p');

        if ($searchFilters->getName()) {
            $qb->andWhere('p.name LIKE :name')
            ->setParameter('name', '%' . $searchFilters->getName() . '%');
        }

        if ($searchFilters->getMinPrice()) {
            $qb->andWhere('p.Price >= :minPrice')
            ->setParameter('minPrice', $searchFilters->getMinPrice());
        }

        if ($searchFilters->getMaxPrice()) {
            $qb->andWhere('p.Price <= :maxPrice')
            ->setParameter('maxPrice', $searchFilters->getMaxPrice());
        }

        // Filter by categories if applicable
        if ($searchFilters->getCategories()) {
            $qb->andWhere('p.Category IN (:categories)')
            ->setParameter('categories', $searchFilters->getCategories());
        }

        return $qb->getQuery()->getResult();
        }
}
//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
 