<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;


class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        // Supprimer le bouton "Nouveau"
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
    
        return $actions;
    }
    
    
    public function configureFields(string $pageName): iterable
    {
        // ...
    
        yield AssociationField::new('commentBy', 'Utilisateur')
            ->formatValue(function ($value, $entity) {
                $userId = $value instanceof User ? $value->getId() : '';
                return sprintf('<a href="/admin/user/%d">%s</a>', $userId, $value instanceof User ? $value->getEmail() : '');
            })
            ->autocomplete()
            ->setFormTypeOptions([
                'required' => true,
            ]);
    
        yield AssociationField::new('article', 'Article')
            ->formatValue(function ($value, $entity) {
                $articleId = $value instanceof Article ? $value->getId() : '';
                return sprintf('<a href="/admin/article/%d">%s</a>', $articleId, $value instanceof Article ? $value->getTitle() : '');
            })
            ->autocomplete()
            ->setFormTypeOptions([
                'required' => true,
            ]);
    
        yield TextField::new('content', 'Contenu du commentaire');
            
        yield BooleanField::new('reported', 'Signalé');
    
        yield DateTimeField::new('createdAt', 'Date et heure');
    
        return parent::configureFields($pageName);
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commentaire')
            ->setEntityLabelInPlural('Commentaires')
            ->setPageTitle('index', 'Liste des Commentaires')
            ->setPageTitle('edit', 'Modifier un Commentaire')
            ->setPageTitle('detail', 'Détails du Commentaire');
    }
}
