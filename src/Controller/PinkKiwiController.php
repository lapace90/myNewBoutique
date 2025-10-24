<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinkKiwiController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        // Récupérer les 8 derniers produits pour la section "Nouveautés"
        $latestProducts = $productRepository->findBy([], ['id' => 'DESC'], 8);
        
        // Récupérer toutes les catégories
        $categories = $categoryRepository->findAll();
        
        // Récupérer 6 produits aléatoires pour la section "Produits en vedette"
        $allProducts = $productRepository->findAll();
        shuffle($allProducts);
        $featuredProducts = array_slice($allProducts, 0, 6);

        return $this->render('home/index.html.twig', [
            'latestProducts' => $latestProducts,
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
        ]);
    }
}