<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
use App\Entity\SearchFilters;
use App\Form\SearchFilterType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

class ProductController extends AbstractController
{
    #[Route('/our-products', name: 'products')]
    public function index(ProductRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $search = new SearchFilters();
        $form = $this->createForm(SearchFilterType::class, $search);
        $form->handleRequest($request);

        // Vérifier d'abord la recherche simple (navbar)
        $simpleSearch = $request->query->get('search');

        if ($simpleSearch && !$form->isSubmitted()) {
            // Recherche simple depuis la navbar
            $query = $repo->searchByName($simpleSearch);
        } elseif ($form->isSubmitted() && $form->isValid()) {
            // Recherche avec filtres avancés (sidebar)
            if ($search->getMinPrice()) {
                $search->setMinPrice($search->getMinPrice() * 100);
            }
            if ($search->getMaxPrice()) {
                $search->setMaxPrice($search->getMaxPrice() * 100);
            }
            $query = $repo->findByFiltersQuery($search);
        } else {
            // Tous les produits
            $query = $repo->createQueryBuilder('p')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
        }

        // Paginer les résultats
        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('product/products.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'searchTerm' => $simpleSearch, // Pour afficher le terme recherché
        ]);
    }

    #[Route('/product/{slug}', name: 'product')]
    public function product(Product $product): Response
    {
        return $this->render('product/oneProduct.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/account/mes-commandes/{slug}/comment', name: 'comment_product')]
    public function comment(Product $product, Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUser($this->getUser());
            $comment->setProduct($product);
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'succes',
                 'Your review for ' . $product->getName() . ' has been successfully saved!'
            );
            return $this->redirectToRoute('product', ['slug' => $product->getSlug()]);
        }
        return $this->render('product/comment.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
