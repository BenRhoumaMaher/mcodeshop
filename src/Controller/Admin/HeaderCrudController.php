<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $required = true;
        if ($pageName === 'edit') {
            $required = false;
        }
        return [
            TextField::new('Title', 'Title'),
            TextareaField::new('content', 'Content'),
            TextField::new('buttonTitle', 'Title of the button'),
            TextField::new('buttonLink', 'URL of the button'),
            ImageField::new('illustration')->setLabel('Image of the header')
                ->setHelp('Image of the header in JPG')
                ->setUploadedFileNamePattern(
                    '[year]-[month]-[day]-[contenthash].[extension]'
                )
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setRequired($required)
        ];
    }
}
