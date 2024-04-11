<?php

namespace App\Controller;

use DateTime;
use App\Entity\Order;
use App\Services\Cart;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


//TODO: verifier TOUS les commentaires
class OrderController extends AbstractController
{
    #[Route('/order', name: 'order')]
    public function index(Request $request, EntityManagerInterface $manager, Cart $cart, ProductRepository $repo): Response
    {
        // if (!$this->getUser()->getAddresses()->getValues())
        //     {
        //         return $this->redirectToRoute('account_address_add');
        //     }
        $form = $this->createForm(OrderType::class, null, [
            'user'=>$this->getUser()
        ]);
        // $cart = $cart->get();
        $cartComplete = [];
        // dd($cart);
        foreach ($cart as $id => $quantity) {
        $cartComplete[] = [
        'product' => $repo->findOneById($id),
        'quantity' => $quantity,
        ];
        }
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            $order = new Order();
            $order->setUser($this->getUser())
            //->setCreatedAt(new DateTime())
            ->setCarrier ($form->get('transporteurs')-> getData())
            ->setDelivery($form->get('addresses')-> getData())
            ->setStatut(0);

            $date = new \DateTime();
            $date = $date->format('dmY');
            $order->setReference($date . '-' . uniqid());
            $manager->persist($order);
            // Enregistrer mes produit OrderDetails
            //dump($cartComplete);
            foreach ($cartComplete as $product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']);
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice());
            //dump($product);
            $manager->persist($orderDetails);
            }
            //$manager->flush();
            // return $this->render('order/recap.html.twig', [
            // 'cart' => $cartComplete,
            // 'order' => $order,
            // ]);

        }
        
        return $this->render('order/order.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
