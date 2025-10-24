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

        if ($form->isSubmitted() && $form->isValid()) {
            // Convertir les prix euros → centimes
            if ($search->getMinPrice()) {
                $search->setMinPrice($search->getMinPrice() * 100);
            }
            if ($search->getMaxPrice()) {
                $search->setMaxPrice($search->getMaxPrice() * 100);
            }

            // Récupérer la query au lieu des résultats
            $query = $repo->findByFiltersQuery($search);
        } else {
            // Récupérer tous les produits sous forme de query
            $query = $repo->createQueryBuilder('p')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
        }

        // Paginer les résultats
        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Numéro de page
            12 // Nombre de produits par page
        );

        return $this->render('product/products.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
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
                'Le commentaire pour le produit ' . $product->getName() . ' a bien été enregistrée !'
            );
            return $this->redirectToRoute('product', ['slug' => $product->getSlug()]);
        }
        return $this->render('product/comment.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
