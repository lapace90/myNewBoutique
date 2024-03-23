<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\SearchFilters;
use App\Form\SearchFilterType;
use App\Repository\ProductRepository;
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
            if (count($search->getCategories())) {
                $categoryIds = [];
                foreach ($search->getCategories() as $category) {
                    $categoryIds[] = $category->getId();
                }
                $products = $repo->findBy(['Category' => $categoryIds]);
            } else {
                $products = $repo->findByFilters($search);
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
        ]);
    }
    

    #[Route('/product/{slug}', name: 'product')]
    public function product(Product $product): Response
    {
        return $this->render('product/oneProduct.html.twig', [
            'product' => $product
        ]);
    }
}
