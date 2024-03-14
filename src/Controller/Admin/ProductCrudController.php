<?php

namespace App\Controller\Admin;

use Faker\Factory;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            //SlugField::new('slug')->setTargetFieldName('name')->onlyWhenCreating(),
            ImageField::new('picture')->setUploadDir('/public/uploads')->setBasePath('uploads/')->setRequired($pageName === Crud::PAGE_NEW)
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
            TextField::new('subtitle'),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            AssociationField::new('Category')
        ];
    }
}

