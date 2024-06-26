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

class ProductController extends AbstractController
{
    #[Route('/our-products', name: 'products')]
    public function index(ProductRepository $repo, Request $request): Response
    {

        $search = new SearchFilters();
        $form = $this->createForm(SearchFilterType::class, $search);
        $form->handleRequest($request);
        $error = null;
        //$products = $repo->findByName("Product's name");

        if ($form->isSubmitted() && $form->isValid()) {
            // Questa if (count(..)) è da togliere. Il filtro per le categorie deve stare dentro $repo->findByFilter()
            if (count($search->getCategories())) {
                $categoryIds = [];
                foreach ($search->getCategories() as $category) {
                    $categoryIds[] = $category->getId();
                }
                $products = $repo->findBy(['Category' => $categoryIds]);
            } else {
                $products = $repo->findByFilters($search);
                // dump($products);
                // dd('ciao');
            }

            if (!$products) {
                $error = "There are no products matching the selected criteria.";
            }
        } else {
            // Se il form non è stato inviato o non è valido, mostra tutti i prodotti
            $products = $repo->findAll();
        }


        return $this->render('product/products.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'error' => $error,
            //'orders' => $repo->FindPaidOrder($this->getUser())
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
