<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResetPasswordRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{

    private $passwordHasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
    }

    #[Route('/password-reset', name: 'reset_password')]
    public function index(Request $request, UserRepository $repo): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        if ($request->get('email')) {
            $user = $repo->findOneByEmail($request->get('email'));

            if ($user) {
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user)
                    ->setToken(uniqid())
                    ->setCreatedAt(new \DateTimeImmutable);

                $this->manager->persist($resetPassword);
                $this->manager->flush();

                $url = $this->generateUrl('update_password', ['token' => $resetPassword->getToken()]);
                $contentEmail = 'Reset password, follow this link to create a new password <br> <a href="' . $_SERVER['HTTP_ORIGIN'] . $url . '">Create your new password</a>';

                mail($user->getEmail(), 'Reset password', $contentEmail);

                $this->addFlash(
                    'success',
                    'A mail has been sent at ' . $_SERVER['HTTP_ORIGIN'] . $url . ' to reset tour password'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'The email ' . $request->get('email') . ' doesn\'t exist, please sign in'
                );
                return $this->redirectToRoute('register');
            }
        }

        return $this->render('reset_password/resetPassword.html.twig', [
            'controller_name' => 'ResetPasswordController',
        ]);
    }

    #[Route('/edit-password/{token}', name: 'update_password')]
    public function update($token, ResetPasswordRepository $repo, Request $request): Response
    {

        $resetPassword = $repo->findOneByToken($token);

        if (!$resetPassword) {

            $this->addFlash(
                'danger',
                'The link has expired'
            );
            return $this->redirectToRoute('home');
        }

        $dateCreate = $resetPassword->getCreatedAt();

        $now = new \DateTime();

        if ($now > $dateCreate->modify('+1 hour')) {

            $this->addFlash(
                'danger',
                'The link has expired'
            );
            return $this->redirectToRoute('reset_password');
        }
        $user = $resetPassword->getUser();
        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                $user->getNewPassword()
            ));
            $this->manager->persist($user); 
            $this->manager->flush(); 
            $this->addFlash(
                'success',
                "Le nouveau mot de passe a bien été créé"
            );
            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
    
