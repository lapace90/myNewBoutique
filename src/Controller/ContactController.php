<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            // Créer et envoyer l'email
            $email = (new Email())
                ->from('noreply@pinkkiwi.com')
                ->replyTo($data['email'])
                ->to($_ENV['ADMIN_EMAIL'] ?? 'admin@pinkkiwi.com')
                ->subject('PinkKiwi - New Contact: ' . $data['subject'])
                ->html($this->renderView('emails/contact.html.twig', [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'subject' => $data['subject'],
                    'message' => $data['message']
                ]));

            try {
                $mailer->send($email);
                
                $this->addFlash(
                    'success',
                    'Thank you for your message! We will get back to you as soon as possible.'
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    'An error occurred while sending your message. Please try again later.'
                );
            }

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}