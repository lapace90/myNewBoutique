<?php

namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private RequestStack $requestStack;
    private EntityManagerInterface $eManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $eManager) 
    {
        $this->requestStack = $requestStack;
        $this->eManager = $eManager;
    }

    public function addToCart(int $id): void
    {

        $cart = $this->requestStack->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->getSession()->set('cart', $cart);
    }

    public function decrease(int $id) {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if ($cart[$id]>1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }
        $this->getSession()->set('cart', $cart);
    }

    public function removeProduct(int $id): Response
     {
        $cart = $this->requestStack->getSession()->get('cart', []);
        unset($cart[$id]);
        $this->getSession()->set('cart', $cart);
        return new Response('Product removed successfully');
    }

    public function removeAll(): Response
{
    $session = $this->getSession();
    if ($session->has('cart')) {
        $session->remove('cart');
        return new Response('Cart cleared successfully');
    } else {
        return new Response('Cart is already empty');
    }
}

    public function getTotal() {
        $cart = $this->getSession()->get('cart');
        if (!$cart || empty($cart)) {
           return []; 
        }
        $cartData = [];
        foreach($cart as $id => $quantity) {
            $product = $this->eManager->getRepository(Product::class)->findOneBy(['id'=>$id]);
            if($product){
                $cartData[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            } else {
                // Gestisci il caso in cui il prodotto non sia trovato nel database
            // Puoi anche rimuovere l'elemento dal carrello se desideri
            }
         
        }
        return $cartData;
    }


    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

}
