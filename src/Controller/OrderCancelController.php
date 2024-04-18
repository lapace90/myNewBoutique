<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
#[Route(path: '/commande/erreur/{stripeSessionId}', name: 'order_cancel')]
public function index(Order $order, EntityManagerInterface $manager): Response
{
if (!$order || $order->getUser() != $this->getUser()) return $this->redirectToRoute('home');

// envoyer un email pour indinquer l'erreur
return $this->render('order_cancel/index.html.twig', [
'order' => $order
]);
}
}