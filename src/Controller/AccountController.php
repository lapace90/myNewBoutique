<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->FindPaidOrder($this->getUser());
        return $this->render('account/account.html.twig', [
            'controller_name' => 'AccountController',
            'orders' => $orders
        ]);
    }
}
