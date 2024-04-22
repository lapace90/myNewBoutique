<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinkKiwiController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(RequestStack $request, OrderRepository $repo): Response
    {
        //dd($repo->MyFindId(1));
        //dd($repo->findOrder('admin@test.fr'));
        // dump($request->getSession()->get('cart')); 
        $cart = $request->getSession()->get('cart', []); // si le cart est vide on renvoit un tableau vide 
        // dump($cart); 
        $cart[12] = 34;
        $request->getSession('cart', $cart);
        // dump($cart); 
        $cart[7] = 6;
        $request->getSession()->set('cart', $cart);
        // dump($cart); 
        $request->getSession()->remove('cart');
        // dump($request->getSession()->get('cart'))

        return $this->render('home/index.html.twig', [

        
        ]);
    }
}
