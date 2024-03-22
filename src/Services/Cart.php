<?php

namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Flex\Response as FlexResponse;

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

    public function removeProduct(int $id): Response
     {
        $cart = $this->requestStack->getSession()->get('cart', []);
        unset($cart[$id]);
        $this->getSession()->set('cart', $cart);
        return new Response('Product removed successfully');
    }

    public function removeAll(){
        return $this->getSession()->remove('cart');
    }

    public function getTotal() {
        $cart = $this->getSession()->get('cart');
        $cartData = [];
        foreach($cart as $id => $quantity) {
            $product = $this->eManager->getRepository(Product::class)->findOneBy(['id'=>$id]);
            if(!$product) {
                //Supprimer le produit puis continuer en sortant de la boucle

            }
            $cartData[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
        return $cartData;
    }


    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

}
