<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Products')
            ->setEntityLabelInPlural('Products')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $required = true;
        if ($pageName === 'edit') {
            $required = false;
        }
        return [
            TextField::new('name')->setLabel('Title')
                ->setHelp('name of the product'),
            BooleanField::new('isHomepage')->setLabel('Homepages Product')
                ->setHelp('you can show a product on the home page'),
            SlugField::new('slug')->setTargetFieldName('name')
                ->setLabel('URL')
                ->setHelp('slug of the product'),
            TextEditorField::new('description')->setLabel('Description')
                ->setHelp('description of the product'),
            ImageField::new('illustration')->setLabel('Image')
                ->setHelp('Image of the product')
                ->setUploadedFileNamePattern(
                    '[year]-[month]-[day]-[contenthash].[extension]'
                )
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setRequired($required),
            NumberField::new('price')->setLabel('Price')
                ->setHelp('price of the product'),
            ChoiceField::new('tva')
                ->setLabel('TVA')
                ->setChoices(
                    [
                    '20%' => '20',
                    '10%' => '10',
                    '5,5%' => '5.5'
                    ]
                ),
            AssociationField::new('category')->setLabel('Category')
                ->setHelp('category of the product'),
        ];
    }
}
