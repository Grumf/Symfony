<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                    [
                        'label' => 'Titre'
                    ]
            )
            ->add(
                'content',
                TextareaType::class,
                    [
                        'label' => 'Contenu'
                    ]
            )
            ->add(
                'description',
                TextareaType::class,
                    [
                        'label' => 'Description'
                    ]
            )
            ->add(
                'category',
                // <select> sur une entité
                EntityType::class,
                    [
                        'label' => 'Catégorie',
                        'class' => Category::class,
                        // Nom du champs qui s'affiche dans les options
                        'choice_label' => 'name',
                        // 1ère option vide
                        'placeholder' => 'Choisissez une catégorie',
                    ]
            )
            ->add(
                'image',
                    // Input type file
                FileType::class,
                    [
                        'label' => 'Illustration',
                        'required' => false
                    ]
            )
        ;
        
        // $options['data'] = L'entité article
        if(!is_null($options['data']->getImage())) {
            $builder->add(
                    'remove_image',
                    CheckboxType::class,
                    [
                        'label' => "Supprimer l'illustration",
                        'required' => false,
                        // La checkbox n'est pas reliée à un attribut de l'entité Article
                        'mapped' => false
                    ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Article::class,
        ]);
    }
}
