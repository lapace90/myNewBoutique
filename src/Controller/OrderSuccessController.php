<?php

namespace App\Controller;


use App\Entity\Order;
use App\Services\Cart;
use Stripe\StripeClient;
use App\Services\StripeService;
use Stripe\Exception\ApiErrorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    #[Route('/account/order/thanks/{stripeSessionId}', name: 'order_success')]
    public function index(Order $order, EntityManagerInterface $manager, Cart $cart, $stripeSessionId): Response
    {
        
        if (!$order || $order->getUser() != $this->getUser()) return $this->redirectToRoute('home');
        
        $stripeSecretKey = $this->getParameter('STRIPE_KEY');
        $stripe = new StripeClient($stripeSecretKey);
        $session = $stripe->checkout->sessions->retrieve($stripeSessionId);
        // dump($session);
        // dd($session->payment_status);
        //si la commande n'est pas payée
        if ($session->payment_status != "paid") return $this->redirectToRoute('order_cancel', ['stripeSessionId' => $stripeSessionId]);
        // modifier statut
        if (!$order->getStatut()) {
            $cart->removeAll();
            $order->setStatut(1);
            $manager->flush();
        }

        return $this->render('order_success/index.html.twig', [
            'total' => $session->amount_total,
            'order' => $order,
        ]);
    }
}
