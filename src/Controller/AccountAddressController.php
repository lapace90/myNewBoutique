<?php

namespace App\Controller;

use App\Services\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    #[Route('/account/address', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account_address/account_address.html.twig', [
            'account_address' => 'AccountAddressController',
        ]);
    }

    #[Route('/account/address_list', name: 'address_list')]
    public function show(): Response
    {
        return $this->redirectToRoute('account_address');
    }

    #[Route('/account/delete_an_address/{id}', name: 'delete_address')]
    public function delete(EntityManagerInterface $manager, Address $address): Response
    {
        if ($address && $address->getUser() == $this->getUser()) {
            $manager->remove($address);
            $manager->flush();
            $this->addFlash(
                'success',
                "The address {$address->getName()} has been successfully deleted"
            );
        }
        return $this->redirectToRoute('account_address');
    }

    #[Route(path: '/account/edit_an_address/{id}', name: 'address_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Address $address): Response
    {
        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($address);
            $manager->flush();
            $this->addFlash(
                'success',
                "The address {$address->getName()} has been successfully updated"
            );
            return $this->redirectToRoute('account_address');
        }
        return $this->render('account_address/add_address.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/add_address', name: 'add_address')]
    public function add(Request $request, EntityManagerInterface $manager, Cart $cart): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $manager->persist($address);
            $manager->flush();
            $this->addFlash(
                'success',
                "The address {$address->getName()} has been successfully created!"
            );
            return $this->redirectToRoute('account_address');
        }
        return $this->render('account_address/add_address.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
