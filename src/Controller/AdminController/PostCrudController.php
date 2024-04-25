<?php

namespace App\Controller\AdminController;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    /**
     * Retourne le nom complet de la classe de l'entité gérée par ce contrôleur CRUD.
     *
     * @return string Le nom complet de la classe de l'entité
     */
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    /**
     * Configure les champs à afficher pour l'entité gérée par ce contrôleur CRUD.
     *
     * @param string $pageName Le nom de la page actuelle (index, new, edit, etc.)
     *
     * @return iterable Les champs à afficher
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
}
