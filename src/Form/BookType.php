<?php

namespace App\Form;

use App\Entity\Books;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('book_title')
            ->add('genre', ChoiceType::class,
                [
                'attr' => ['placeholder' => 'Please select your book genre'],
                'choices' => [
                    'Fiction' => 'Fiction',
                    'Non-Fiction' => 'Non-Fiction',
                    'Adventure ' => 'Adventure',
                    'Thriller'  => 'Triller' ,
                    'Horror'   => 'Horror' ,
                    'Others'  => 'Others',
                ],
            // 'expanded' => true
            ]
            )
            ->add('Author')
            ->add('submit' ,SubmitType::class, ['label'=>'Publish'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Books::class,
        ]);
    }
}
