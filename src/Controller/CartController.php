<?php

namespace App\Controller;

use App\Services\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CartController extends AbstractController
{

    #[Route('/my-cart', name: 'my_cart')]
    public function index(Cart $cart): Response
    {
        // dd($cart->getTotal());

        return $this->render('cart/cart.html.twig', [
            'cart' => $cart->getTotal()
        ]);
    }

    #[Route(path: '/my-cart/add/{id}', name: 'add_to_cart')]
    public function addToRoute(int $id, Cart $cart): Response
    {
        // dd($cart);
        $cart->addToCart($id);
        return $this->redirectToRoute('my_cart');
    }

    #[Route(path: '/my-cart/delete/{id}', name: 'delete_product')]
    public function removeProduct(Cart $cart, int $id): Response
    {
        // dd($cart);
        $cart->removeProduct($id);
        return $this->redirectToRoute('my_cart');
    }

    #[Route(path: '/my-cart/decrease/{id}', name: 'decrease_item')]
    public function decrease(int $id, Cart $cart): RedirectResponse
    {
        // dd($cart);
        $cart->decrease($id);
        return $this->redirectToRoute('my_cart');
    }

    #[Route(path: '/my-cart/removeAll', name: 'purge-cart')]
    public function removeAll(Cart $cart): Response
    {
        // dd($cart);
        $cart->removeAll();
        return $this->redirectToRoute('my_cart');
    }

//     #[Route(path: '/cart/delete/{id}', name: 'delete_to_cart')]
//     public function delete(Cart $cart, $id): Response
//     {
//         $cart->delete($id);

//         return $this->redirectToRoute('cart');
//     }
 }
