<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $passwordHasher;
    private $manager;
    private $mailer;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher, 
        EntityManagerInterface $manager,
        MailerInterface $mailer
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
        $this->mailer = $mailer;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Genera un token di attivazione
            $token = sha1($user->getEmail() . $user->getPassword());

            // Invia l'email di attivazione
            $content_mail = 'Bonjour ' . $user->getFirstName() . ' ' . $user->getLastName() . ', <br><br>
                Merci de vous être inscrit sur My Boutique. Votre compte a été créé et doit être activé avant que vous puissiez
                l\'utiliser.<br>
                Pour l\'activer, cliquez sur le lien ci-dessous ou copiez et collez le dans votre navigateur :<br><a href="https://' .
                $_SERVER['HTTP_HOST'] . '/inscription/' . $user->getEmail() . '/' . $token . '" style="color: #5cff00">https://' .
                $_SERVER['HTTP_HOST'] . '/inscription/' . $user->getEmail() . '/' . $token . '</a><br><br>
                Après activation vous pourrez vous connecter à <a href="https://www.myboutique.com/" style="color:
                #5cff00">https://www.myboutique.com/</a> en utilisant l\'identifiant et le mot de passe suivants :<br>
                Identifiant : ' . $user->getEmail() . '<br>';

                $email = (new Email())
                ->from('noreply@example.com')
                ->to($user->getEmail())
                ->subject('Détails du compte utilisateur de ' . $user->getFirstName() . ' ' . $user->getLastName() . ' sur My boutique')
                ->html($content_mail);

            $this->mailer->send($email);
            // Persiste l'utente nel database
            if ($user->getPassword()) {
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                $user->setPassword($hashedPassword);
            }
        
            $this->manager->persist($user);
            $this->manager->flush();
        

            // Reindirizza all'area di login
            $this->addFlash(
                'success',
                'Your account ' . $user->getEmail() . ' was successfully created! :)'
            );
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/inscription/{email}/{token}', name: 'register_active')]
    public function Register_active(User $user, $token): Response
    {
        $token_verif = sha1($user->getEmail() . $user->getPassword());
        // dump($token);
        //dump($token_verif);
        // dd($user);
        if (!$user->isActive()) {
            if ($token == $token_verif) {
                $user->setActive(true);
                $this->manager->flush();
                $this->addFlash(
                    'success',
                    "The account has been activated"
                );
                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash(
                    'danger',
                    "Invalid activation's link"
                );
                return $this->redirectToRoute('home');
            }
        } else {
            $this->addFlash(
                'success',
                "Account already active"
            );
            return $this->redirectToRoute('app_login');
        }
    }
}
