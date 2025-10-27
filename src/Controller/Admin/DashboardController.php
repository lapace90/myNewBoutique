<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Carrier;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Config;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $order;

    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect(
            $routeBuilder
                ->setController(OrderCrudController::class)
                ->generateUrl()
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('PinkKiwi Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fas fa-home');
        
        yield MenuItem::section('Configuration');
        yield MenuItem::linkToCrud('Configuration', 'fas fa-gears', Config::class);
        
        yield MenuItem::section('Admin');
        yield MenuItem::linkToCrud('Admin', 'fas fa-users', User::class)
            ->setController(AdminUserCrudController::class);
        
        yield MenuItem::section('User');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        
        yield MenuItem::section('Catalog');
        yield MenuItem::linkToCrud('Categories', 'fas fa-folder', Category::class);
        yield MenuItem::linkToCrud('Products', 'fas fa-tags', Product::class);
        yield MenuItem::linkToCrud('Carriers', 'fas fa-truck', Carrier::class);
        yield MenuItem::linkToCrud('Comments', 'fas fa-comments', Comment::class);

        $nbrsOrderWait = count($this->order->findBy(['statut' => 0]));
        $nbrsOrderOk = count($this->order->findBy(['statut' => 1]));

        yield MenuItem::section('Orders');
        yield MenuItem::linkToCrud(
            'Orders <span style="color:green;font-weight:bold" class="badge badge-success">' .
                $nbrsOrderOk . '</span> <span style="color:red;font-weight:bold" class="badge badge-danger">' . 
                $nbrsOrderWait . '</span>',
            'fas fa-shopping-cart',
            Order::class
        );
    }
}