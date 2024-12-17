<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\User;
use App\Repository\CategorieRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre de l\'article'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Catégories',
                'expanded' => false,
                'multiple' => true,
                'choice_label' => 'name',
                'by_reference' => false,
                'autocomplete' => true,
                'required' => false,
                'query_builder' => function (CategorieRepository $repo): QueryBuilder {
                    return $repo
                        ->createQueryBuilder('c')
                        ->andWhere("c.enabled = :enabled")
                        ->setParameter('enabled', true)
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'placeholder' => 'Contenu de l\'article',
                    'rows' => 10
                ]
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Publié',
                'required' => false
            ]);

        if ($options['isEdit']) {
            $builder
                ->add('user', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => 'fullName',
                    'label' => 'Auteur',
                    'multiple' => false,
                    'expanded' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'isEdit' => false,
        ]);
    }
}
