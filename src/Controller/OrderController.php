<?php

namespace App\Controller;

use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'order')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        // if (!$this->getUser()->getAddresses()->getValues())
        //     {
        //         return $this->redirectToRoute('account_address_add');
        //     }
        $form = $this->createForm(OrderType::class, null, [
            'user'=>$this->getUser()
        ]);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){

        }
        
        return $this->render('order/order.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
