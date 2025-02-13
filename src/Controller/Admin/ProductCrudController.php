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
use EasyCorp\Bundle\EasyAdminBundle\Field\FileField;
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
            ->setEntityLabelInSingular(t('PRODUCT.ENTITY_LABEL', [], 'admin'))
            ->setEntityLabelInPlural(t('PRODUCT.ENTITIES_LABEL', [], 'admin'))
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $imageField = ImageField::new('image', t('PRODUCT.IMAGE', [], 'admin'))
            ->setFileConstraints(new Image(mimeTypes: ['image/jpeg', 'image/png', 'image/gif']))
            ->setUploadedFileNamePattern('/uploads/[slug]-[contenthash].[extension]')
            ->setBasePath('/')
            ->setUploadDir('public/uploads')
            ->setSortable(false);

        if (Crud::PAGE_EDIT === $pageName) {
            $imageField->setRequired(false);
        }

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', t('PRODUCT.NAME', [], 'admin')),
            $imageField,
            TextareaField::new('description', t('PRODUCT.DESCRIPTION', [], 'admin')),
            MoneyField::new('priceCents', t('PRODUCT.PRICE', [], 'admin'))->setCurrency('EUR'),
            SlugField::new('slug')->setTargetFieldName('name')->setUnlockConfirmationMessage(t('PRODUCT.SLUG_WARNING', [], 'admin'))->hideOnIndex(),
        ];
    }

}
