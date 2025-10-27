<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    #[Route('/account/order', name: 'account_order')]
    public function index(OrderRepository $repo): Response
    {
        return $this->render('account_order/index.html.twig', [
            'orders' => $repo->FindPaidOrder($this->getUser())
        ]);
    }

    #[Route(path: '/account/my-orders/{reference}', name: 'account_order_show')]
        public function show(Order $order): Response
        {
            if (!$order || $order->getUser() != $this->getUser()) {
                return $this->redirectToRoute('account_order');
            }
            return $this->render('order/order_show.html.twig', [
                'order' => $order
            ]);
        }
}
