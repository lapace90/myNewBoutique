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

        // Filtre par nom
        if ($searchFilters->getName()) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $searchFilters->getName() . '%');
        }

        // Filtre par prix minimum
        if ($searchFilters->getMinPrice()) {
            $qb->andWhere('p.Price >= :minPrice')
                ->setParameter('minPrice', $searchFilters->getMinPrice());
        }

        // Filtre par prix maximum
        if ($searchFilters->getMaxPrice()) {
            $qb->andWhere('p.Price <= :maxPrice')
                ->setParameter('maxPrice', $searchFilters->getMaxPrice());
        }

        // Filtre par catégories
        if (count($searchFilters->getCategories()) > 0) {
            $qb->andWhere('p.category IN (:categories)')
                ->setParameter('categories', $searchFilters->getCategories());
        }

        // Tri par prix
        $qb->orderBy('p.Price', 'ASC');

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
    public function findByFiltersQuery(SearchFilters $searchFilters)
    {
        $qb = $this->createQueryBuilder('p');

        // Filtre par nom
        if ($searchFilters->getName()) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $searchFilters->getName() . '%');
        }

        // Filtre par prix minimum
        if ($searchFilters->getMinPrice()) {
            $qb->andWhere('p.Price >= :minPrice')
                ->setParameter('minPrice', $searchFilters->getMinPrice());
        }

        // Filtre par prix maximum
        if ($searchFilters->getMaxPrice()) {
            $qb->andWhere('p.Price <= :maxPrice')
                ->setParameter('maxPrice', $searchFilters->getMaxPrice());
        }

        // Filtre par catégories
        if (count($searchFilters->getCategories()) > 0) {
            $qb->andWhere('p.category IN (:categories)')
                ->setParameter('categories', $searchFilters->getCategories());
        }

        // Tri par prix
        $qb->orderBy('p.Price', 'ASC');

        return $qb->getQuery();
    }

    /**
     * Recherche simple par nom de produit avec ordre de pertinence
     */
    public function searchByName(string $searchTerm)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where($qb->expr()->orX(
                $qb->expr()->like('p.name', ':search'),
                $qb->expr()->like('p.subtitle', ':search'),
                $qb->expr()->like('p.Description', ':search')
            ))
            ->setParameter('search', '%' . $searchTerm . '%')
            // Prioriser par ordre : nom > subtitle > description
            ->addSelect(
                '(CASE 
                WHEN p.name LIKE :searchTerm THEN 1
                WHEN p.subtitle LIKE :searchTerm THEN 2
                ELSE 3
            END) AS HIDDEN relevance'
            )
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('relevance', 'ASC')
            ->addOrderBy('p.id', 'DESC')
            ->getQuery();
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
