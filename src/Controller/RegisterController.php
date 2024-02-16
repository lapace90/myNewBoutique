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

        //dump($user);

        $form->handleRequest($request);
        //dd($user);

        if ($form->isSubmitted() && $form->isValid()) {


            //On recupère le password non codé:
            //$plaintextPassword = $user->getPassword();

            // hash the password (based on the security.yaml config for the $user class)
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );


            //persiste les données dans le temps
            $this->manager->persist($user);

            //écrit dans la BD
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Your account ' . $user->getEmail() . ' was successfully created! :)'
            );

            // $this->addFlash(
            //     'info',
            //     'Holy guacamole! Check your infos!'
            // );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
