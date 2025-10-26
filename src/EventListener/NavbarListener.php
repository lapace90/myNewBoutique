<?php

namespace App\EventListener;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class NavbarListener
{
    private $twig;
    private $categoryRepository;

    public function __construct(Environment $twig, CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->categoryRepository = $categoryRepository;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // Rendre les catégories disponibles dans tous les templates
        $categories = $this->categoryRepository->findAll();
        $this->twig->addGlobal('categories', $categories);
    }
}