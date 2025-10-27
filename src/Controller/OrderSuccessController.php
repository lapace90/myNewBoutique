<?php

namespace App\Controller;

use App\Entity\Order;
use App\Services\Cart;
use Stripe\StripeClient;
use App\Services\StripeService;
use Symfony\Component\Mime\Email;
use Stripe\Exception\ApiErrorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $stripeService;
    private $mailer;

    public function __construct(StripeService $stripeService, MailerInterface $mailer)
    {
        $this->stripeService = $stripeService;
        $this->mailer = $mailer;
    }

    #[Route('/account/order/thanks/{stripeSessionId}', name: 'order_success')]
    public function index(Order $order, EntityManagerInterface $manager, Cart $cart, $stripeSessionId): Response
    {
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        
        $stripeSecretKey = $this->getParameter('STRIPE_KEY');
        $stripe = new StripeClient($stripeSecretKey);
        $session = $stripe->checkout->sessions->retrieve($stripeSessionId);
        
        // If payment failed, redirect to error page
        if ($session->payment_status != "paid") {
            return $this->redirectToRoute('order_cancel', ['stripeSessionId' => $stripeSessionId]);
        }
        
        // Update order status and send confirmation email
        if (!$order->getStatut()) {
            $cart->removeAll();
            $order->setStatut(1);
            $manager->flush();
            
            // Send confirmation email
            $this->sendOrderConfirmationEmail($order);
        }

        return $this->render('order_success/index.html.twig', [
            'total' => $session->amount_total,
            'order' => $order,
        ]);
    }

    private function sendOrderConfirmationEmail(Order $order): void
    {
        try {
            $email = (new Email())
                ->from('noreply@pinkkiwi.com')
                ->to($order->getUser()->getEmail())
                ->subject('Order Confirmation #' . $order->getReference())
                ->html($this->renderView('emails/order_confirmation.html.twig', [
                    'order' => $order
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Log error but don't fail the order
            // In production you would log this properly
            error_log('Failed to send order confirmation email: ' . $e->getMessage());
        }
    }
}