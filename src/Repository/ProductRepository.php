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
    public function findByFilters(SearchFilters $searchFilters) {
        $qb = $this->createQueryBuilder('p');
        dump($searchFilters);
        if ($searchFilters->getName()) {
            dump($searchFilters->getName());
            $qb->andWhere('p.name LIKE :name')
            ->setParameter('name', '%'. $searchFilters->getName() .'%');
        }

        if ($searchFilters->getMinPrice()) {
            // dump($searchFilters->getMinPrice());
            $qb->andWhere('p.Price >= :minPrice')
            ->setParameter('minPrice', $searchFilters->getMinPrice())
            ->orderBy('p.Price', 'DESC');
        }

        if ($searchFilters->getMaxPrice()) {
            // dump($searchFilters->getMaxPrice());
            $qb->andWhere('p.Price <= :maxPrice')
            ->setParameter('maxPrice', $searchFilters->getMaxPrice())
            ->orderBy('p.Price', 'DESC');
        }

        if (count($searchFilters->getCategories())) {
            // fare join con categories table e product table
            $qb->andWhere('p.category_id IN (:categories)')
            ->setParameter('categories', $searchFilters->getCategories()); // pippo, caio 
            // pippo = 3
            // caio = 5
        }

        // dump($qb->getQuery()->getSQL());
        // dump($qb->getQuery()->getParameters());
        // dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getResult();
    }

    public function MyFindId($id)
    {
        //createQueryBuilder('p') =>SELECT p FROM APP\ENTITY\PRODUCT p

        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id > :id')
            ->setParameter('id', $id);
            // ->orderBy('p.arg', 'ASC')
            // ->setMaxResults(10)
            // ->getQuery()
            // ->getResult();

            //on recupère la requête
            $query = $queryBuilder->getQuery();

            //on recupère les resultats
            $result = $query->getOneOrNullResult();

            return $result;
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
 