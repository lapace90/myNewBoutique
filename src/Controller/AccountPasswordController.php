<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
            //recupère l'user connecté

            private $passwordHasher;
            private $manager;
            public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager)
        {
            $this->passwordHasher = $passwordHasher;
            $this->manager= $manager;
        }

    #[Route('/account/edit_password', name: 'account_password')]
    public function index(Request $request): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid()) {
            
            if(!$this->passwordHasher->isPasswordValid(
                $user, $user->getOldPassword())) {

                    $this->addFlash(
                        'danger',
                        'The old password is not valid'
                    );
                } else {
                    $hashedPassword = $this->passwordHasher->hashPassword(
                        $user,
                        $user->getNewPassword()
                    );
                    $user->setPassword($hashedPassword);

                    $this->manager->persist($user);

                    //écrit dans la BD
                    $this->manager->flush();
                    
                $this->addFlash(
                    'success',
                    'Your password was successfully edited! :)'
                );

                    return $this->redirectToRoute('app_login');
                }
        }

        return $this->render('account/accountPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
