<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title')
            ->add('publicationDate', null, [
                'widget' => 'single_text',
            ])
            ->add('enabled')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Science-Fiction' => 'science_fiction',
                    'Mystery' => 'mystery',
                    'Autobiography' => 'autobiography',
                ],
                'label' => 'Category',
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'UserName',  // Affiche le nom de l'auteur au lieu de l'ID
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
