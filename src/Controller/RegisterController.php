<?php

namespace App\Controller;



use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{

    private $passwordHasher;
    private $manager;


    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
    }


    #[Route('/inscription', name: 'register')]
    public function index(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $user->setActive(0);
            // persiste les données dans le temps
            $this->manager->persist($user);

            //ecrit dans la bdd
            $this->manager->flush();

            $token = sha1($user->getEmail() . $user->getPassword());

            // Envoi d'un mail
            $contentEmail = 'Hello ' . $user->getEmail() . '<br>
Thank you for your registration, the account has been created and must be activated via the link below<br>
https://' . $_SERVER['HTTP_HOST'] . '/inscription/' . $user->getId() . '/' . $token;
            mail($user->getEmail(), 'Account Activation', $contentEmail);

            $this->addFlash(
                'success',
                'The account ' . $user->getEmail() . ' has been created and must be activated, an email has been sent to you'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/inscription/{id}/{token}', name: 'registerActivation')]
    public function activation(Request $request, User $user, $token): Response
    {
        if (!$user->isActive()) {

            $verifToken = sha1($user->getEmail() . $user->getPassword());

            if ($token == $verifToken) {

                $user->setActive(true);

                $this->manager->flush();

                $this->addFlash(
                    'success',
                    'Account successfully activated'
                );

                return $this->redirectToRoute('account');
            } else {

                $this->addFlash(
                    'danger',
                    'Incorrect link'
                );

                return $this->redirectToRoute('account');
            }
        } else {

            $this->addFlash(
                'success',
                 'The account ' . $user->getEmail() . ' is already activated'
            );

            return $this->redirectToRoute('account');
        }
    }
}
