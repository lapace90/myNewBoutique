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
            $contentEmail = 'Bonjour' . $user->getEmail() . '<br>
        Merci de votre inscription, le compte a été créé et doit être activé via le lien ci-dessous<br>
        https://' . $_SERVER['HTTP_HOST'] . '/inscription/' . $user->getId() . '/' . $token;
            mail($user->getEmail(), 'Activation de compte', $contentEmail);


            $this->addFlash(
                'success',
                'Le compte ' . $user->getEmail() . ' a bien été créé et doit être activé, un mail vous a été envoyé'
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

        // dd($user);



        if (!$user->isActive()) {

            $verifToken = sha1($user->getEmail() . $user->getPassword());

            if ($token == $verifToken) {

                // dd('ok');
                $user->setActive(true);

                $this->manager->flush();

                $this->addFlash(
                    'success',
                    'Compte activé avec succes'
                );

                return $this->redirectToRoute('account');
            } else {

                $this->addFlash(
                    'danger',
                    'Lien incorrect'
                );

                return $this->redirectToRoute('account');
            }
        } else {

            $this->addFlash(
                'success',
                'Le compte ' . $user->getEmail() . ' est déjà activé'
            );

            return $this->redirectToRoute('account');
        }
    }
}
