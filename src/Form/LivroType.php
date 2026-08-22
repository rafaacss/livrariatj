<?php

namespace App\Form;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titulo', TextType::class, [
                'label' => 'Título',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Digite o título do livro',
                ],
            ])

            ->add('Editora', TextType::class, [
                'label' => 'Editora',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Digite a editora',
                ],
            ])

            ->add('Edicao', IntegerType::class, [
                'label' => 'Edição',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex.: 18',
                ],
            ])

            ->add('AnoPublicacao', TextType::class, [
                'label' => 'Ano de publicação',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex.: 2024',
                    'maxlength' => 4,
                ],
            ])

            ->add('Valor', NumberType::class, [
                'label' => 'Valor',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex.: 133.90',
                ],
            ])

            ->add('assuntos', EntityType::class, [
                'class' => Assunto::class,
                'choice_label' => 'Descricao',
                'choice_value' => 'codAs',
                'multiple' => true,
                'label' => 'Assuntos',
                'attr' => [
                    'class' => 'form-select',
                    'size' => 5
                ],
            ])

            ->add('autores', EntityType::class, [
                'class' => Autor::class,
                'choice_label' => 'Nome',
                'choice_value' => 'CodAu',
                'multiple' => true,
                'label' => 'Autores',
                'attr' => [
                    'class' => 'form-select',
                    'size' => 5
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livro::class,
        ]);
    }
}
