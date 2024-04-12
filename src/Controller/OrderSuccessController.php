<?php

namespace App\Controller;

use App\Entity\Order;
use App\Services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    #[Route('/account/order/thanks/{CHECKOUT_SESSION_ID}', name: 'order_success')]
    public function index(Order $order, EntityManagerInterface $manager, Cart $cart, $stripeSessionId): Response
    {
        if (!$order || $order->getUser() != $this->getUser()) return $this->redirectToRoute('home');

        $stripeSessionId = '{CHECKOUT_SESSION_ID}';
        $stripeSecretKey = $this->getParameter('STRIPE_KEY');
        $stripe = new StripeClient($stripeSecretKey);
        $session = $stripe->checkout->sessions->retrieve($stripeSessionId);
        // dump($session);
        // dd($session->payment_status);
        //si la commande n'est pas payée
        if ($session->payment_status != "paid") return $this->redirectToRoute('order_cancel', ['stripeSessionId' => $stripeSessionId]);
        // modifier statut
        if (!$order->getStatut()) {
        // vider la session cart (le panier)
        $cart->removeAll();
        $order->setStatut(1);
        //$manager->flush();
        }
        // envoyer un email

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
