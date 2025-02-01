<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Image;
use function Symfony\Component\Translation\t;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', t('PRODUCT.NAME')),
            ImageField::new('image', t('PRODUCT.IMAGE'))
                ->setFileConstraints(new Image(mimeTypes: ['image/jpeg', 'image/png', 'image/gif']))
                ->setUploadedFileNamePattern('/uploads/[slug]-[contenthash].[extension]')
                ->setBasePath('/')
                ->setUploadDir('public/uploads')
                ->setRequired(false)
                ->setSortable(false),
            TextareaField::new('description', t('PRODUCT.DESCRIPTION')),
            MoneyField::new('priceCents', t('PRODUCT.PRICE'))->setCurrency('EUR'),
            SlugField::new('slug')->setTargetFieldName('name')->setUnlockConfirmationMessage(t('ADMIN.PRODUCT.SLUG_WARNING'))->hideOnIndex(),
        ];
    }

}
