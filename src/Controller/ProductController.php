<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SearchFilters;
use App\Form\SearchFilterType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    #[Route('/our-products', name: 'products')]
    public function index(ProductRepository $repo, Request $request): Response
    {


        $search = new SearchFilters();
        $form = $this->createForm(SearchFilterType::class, $search);
        $form->handleRequest($request);
        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            if (count($search->getCategories())) {
                foreach ($search->getCategories() as $category) {

                    $tabId[] = $category->getId();
                }
                // $id = $search->getCategories();
                $products = $repo->findBy(['Category' => $tabId]);
            } else {
                $products = $repo->findAll();
            }

            // dd($search->getCategories());

            if (!$products) {
                $error = "There is any product";
            }
        } else {
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
        //dd($product);
        return $this->render('product/oneProduct.html.twig', [
            'product' => $product
        ]);
    }
}
