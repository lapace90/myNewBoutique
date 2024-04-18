<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Order;
use App\Services\Cart;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Stripe\Checkout\Session;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


//TODO: verifier TOUS les commentaires
class OrderController extends AbstractController
{
    #[Route('/order', name: 'order')]
    public function index(#[CurrentUser] ?User $user, Request $request, EntityManagerInterface $manager, Cart $cart, ProductRepository $repo): Response
    {



        if (!$user->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }


        $cart = $cart->get();
        $cartComplete = [];
        foreach ($cart as $id => $quantity) {
            $cartComplete[] = [
                'product' => $repo->findOneById($id),
                'quantity' => $quantity,
            ];
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTime();
            $date = $date->format('dmY');
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setCarrier($form->get('transporteurs')->getData());
            $order->setDelivery($form->get('addresses')->getData());
            $order->setStatut(0);

            $order->setReference($date . '-' . uniqid());
            $manager->persist($order); // Enregistrer mes produit OrderDetails 
            //dump($cartComplete); 
            foreach ($cartComplete as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']);
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice()); //dump($product); 
                $manager->persist($orderDetails);

                $stripe_products[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $product['product']->getName(),
                            'images' => [
                                $product['product']->getPicture()
                                //$_SERVER['HTTP_ORIGIN'] . '/uploads' . $product['product']->getPicture()
                            ]
                        ],
                        'unit_amount' => $product['product']->getPrice(),
                    ],
                    'quantity' => $product['quantity'],
                ];
                $stripe_products[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $order->getCarrier()->getName(),
                        ],
                        'unit_amount' => $order->getCarrier()->getPrice(),
                    ],
                    'quantity' => 1,
                ];
            }


            $YOUR_DOMAIN = $_SERVER['HTTP_ORIGIN'];
            $stripeSecretKey = $this->getParameter('STRIPE_KEY');

            Stripe::setApiKey($stripeSecretKey);

            $checkout_session = Session::create([
                'line_items' => $stripe_products,
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/account/order/thanks/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/account/order/error/{CHECKOUT_SESSION_ID}',
            ]);
            $order->setStripeSessionId($checkout_session->id);

            //dd($checkout_session->url);
            $manager->flush();

            return $this->render('order/order/recap.html.twig', [
                'cart' => $cartComplete,
                'order' => $order,
                'stripe_checkout_session' => $checkout_session->url
            ]);
        }
        return $this->render('order/order/order.html.twig', [
            'form' => $form->createView(),
            'cart' => $cartComplete,
        ]);
    }
}
