<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/our-products', name: 'products')]
    public function index(ProductRepository $repo): Response
    {
        $products = $repo->findAll(); //recupère tous les enregistrements de la table visée
        //dump($products);
        return $this->render('product/products.html.twig', [
            'products' => $products
        ]);
       
    }

    #[Route('/product/{slug}', name: 'product')]
    public function product(Product $product): Response {
        //dd($product);
        return $this->render('product/oneProduct.html.twig', [
            'product'=>$product
        ]);
    }
}